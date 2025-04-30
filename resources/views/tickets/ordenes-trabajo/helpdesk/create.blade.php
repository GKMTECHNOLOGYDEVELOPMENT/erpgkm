<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        .nice-select {
            display: flex !important;
            align-items: center !important;
            padding-top: 0.5rem !important;
            padding-bottom: 0.5rem !important;
            border-radius: 0.375rem !important;
            /* igual a rounded-md */
            border: 1px solid #d1d5db !important;
            /* igual a border-gray-300 */
            width: 100% !important;
            font-size: 0.875rem !important;
            /* igual a text-sm */
            height: auto !important;
            min-height: 2.5rem;
            /* similar a h-10 */
        }

        .nice-select .current {
            line-height: normal !important;
            width: 100%;
        }
    </style>


    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse mt-4">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Órdenes</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Agregar Orden de Trabajo</span>
            </li>
        </ul>
    </div>

    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Orden de Trabajo</h2>



        <div class="p-5">
            <form id="ordenTrabajoForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('ordenes.storehelpdesk') }}">
                @csrf
                <!-- Número de Ticket -->
                <div>
                    <label for="numero_ticket" class="block text-sm font-medium">N. Ticket</label>
                    <input id="numero_ticket" name="numero_ticket" type="text" class="form-input w-full"
                        placeholder="Ingrese el número de ticket">
                    <p id="errorTicket" class="text-sm text-red-500 mt-2 hidden"></p>

                </div>

                <!-- Cliente -->
                <div>
                    <label for="idCliente" class="block text-sm font-medium">Cliente</label>
                    <select id="idCliente" name="idCliente" class="form-input w-full">
                        <option value="" disabled selected>Seleccionar Cliente</option>
                        <!-- Los clientes se cargarán dinámicamente aquí con JS -->
                    </select>
                </div>

                <!-- Cliente General -->
                <div>
                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                    <select id="idClienteGeneral" name="idClienteGeneral" class="form-input w-full ">
                        <option value="" selected>Seleccionar Cliente General</option>
                    </select>
                </div>


                <!-- Select para tiendas (solo si el cliente es tienda) -->
                <div id="selectTiendaContainer">
                    <label for="idTienda" class="block text-sm font-medium">Tienda</label>
                    <select id="idTienda" name="idTienda" class="form-input w-full">
                        <option value="">Seleccionar Tienda</option>
                    </select>
                </div>

                <!-- Tipo de Servicio -->
                <div>
                    <label for="tipoServicio" class="block text-sm font-medium">Tipo de Servicio</label>
                    <select id="tipoServicio" name="tipoServicio" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Tipo de Servicio</option>
                        @foreach ($tiposServicio as $tipo)
                            <option value="{{ $tipo->idTipoServicio }}" data-nombre="{{ $tipo->nombre }}">
                                {{ $tipo->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Falla Reportada - debajo de tipo de servicio y ocupando 2 columnas -->
                <div class="col-span-2">
                    <label for="fallaReportada" class="block text-sm font-medium">Falla Reportada</label>
                    <textarea id="fallaReportada" name="fallaReportada" rows="3" class="form-input w-full"
                        placeholder="Describa la falla reportada"></textarea>
                </div>


                <!-- Switches en 2 columnas -->
                <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- ¿Es Recojo? -->
                    <!-- <div id="esRecojoContainer" class="hidden">
                        <label class="block text-sm font-medium mb-2">¿Es Recojo?</label>
                        <label class="w-12 h-6 relative inline-block">
                            <input type="checkbox" id="esRecojo" name="esRecojo"
                                class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                            <span
                                class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300">
                            </span>
                        </label>
                    </div> -->




                    <!-- Dentro del formulario -->
                    <div id="esEquipoContainer" class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                        <!-- Tipo Producto -->
                        <div>
                            <label class="block text-sm font-medium">Tipo Producto</label>
                            <select id="tipoProductoLab" name="tipoProducto" class="form-select select2 w-full">
                                <option value="" disabled selected>Seleccionar Tipo de Producto</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Marca -->
                        <div>
                            <label class="block text-sm font-medium">Marca</label>
                            <select id="marcaLab" name="marca" class="form-select select2 w-full">
                                <option value="" disabled selected>Seleccionar Marca</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Modelo -->
                        <div>
                            <label class="block text-sm font-medium">Modelo</label>
                            <select id="modeloLab" name="modelo" class="form-select select2 w-full hidden">
                                <option value="" disabled selected>Seleccionar Modelo</option>
                            </select>
                        </div>



                        <!-- Número de Serie -->
                        <div>
                            <label class="block text-sm font-medium">Nro. de Serie</label>
                            <input type="text" id="serieRetirar" name="serieRetirar" class="form-input w-full"
                                placeholder="Ingrese Nro. de Serie">
                        </div>

                        <!-- Observaciones (ocupa ambas columnas internas) -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium">Observaciones</label>
                            <textarea id="observacionesRetirar" name="observaciones" class="form-textarea w-full" rows="3"
                                placeholder="Ingrese observaciones (opcional)"></textarea>
                        </div>
                    </div>

               <!-- numero cotizacion -->
              <div id="numeroCotizacionContainer" style="display: none;">
                        <label class="block text-sm font-medium mb-2">Numero Cotizacion</label>
                       
                        <input type="text" id="numerocotizacion" name="nrmcotizacion" class="form-input w-full"
                        placeholder="Ingrese Nro. Cotizacion">
                        </label>
                    </div>


                    <!-- ¿Es Envío? -->
                    <div id="esEnvioContainer" style="display: none;">
                        <label class="block text-sm font-medium mb-2">¿Es Envío?</label>
                        <label class="w-12 h-6 relative inline-block">
                            <input type="hidden" name="esEnvio" value="0">
                            <input type="checkbox" id="esEnvio" name="esEnvio" value="1"
                                class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                            <span
                                class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300">
                            </span>
                        </label>
                    </div>
                </div>

                <!-- Técnico, Recojo, Envío en 2 columnas (2 arriba, 1 abajo) -->
                <div id="tecnicoContainer" style="display: none;"
                    class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Agencia -->
                    <div>
                        <label class="block text-sm font-medium">Corruir</label>
                        <input type="text" name="agencia" class="form-input w-full"
                            placeholder="Ingrese el Agencia">
                    </div>
                    <!-- Técnico -->
                    <div>
                        <label for="idTecnico" class="block text-sm font-medium">Técnico Envío</label>
                        <select id="idTecnico" name="idTecnico" class="select2 w-full mb-2" style="display: none">
                            <option value="" disabled selected>Seleccionar Técnico</option>
                            @foreach ($usuarios as $usuario)
                                <option value="{{ $usuario->idUsuario }}">{{ $usuario->Nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo de Recojo -->
                    <div>
                        <label for="tipoRecojo" class="block text-sm font-medium">Tipo de Recojo</label>
                        <select id="tipoRecojo" name="tipoRecojo" class="select2 w-full mb-2" style="display: none">
                            <option value="" disabled selected>Seleccionar Tipo de Recojo</option>
                            @foreach ($tiposRecojo as $tipo)
                                <option value="{{ $tipo->idtipoRecojo }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tipo de Envío (en una fila aparte ocupando 2 columnas) -->
                    <div class="md:col-span-2">
                        <label for="tipoEnvio" class="block text-sm font-medium">Tipo de Envío</label>
                        <select id="tipoEnvio" name="tipoEnvio" class="select2 w-full mb-2" style="display: none">
                            <option value="" disabled selected>Seleccionar Tipo de Envío</option>
                            @foreach ($tiposEnvio as $tipoEnvio)
                                <option value="{{ $tipoEnvio->idtipoenvio }}">{{ $tipoEnvio->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>





                <!-- Datos del Técnico de Envío -->
                <div id="tecnicoDatosContainer" style="display: none;" class="col-span-2">
                    <!-- Título con estilo de badge -->
                    <div class="mb-4">
                        <span class="badge badge-outline-primary text-base md:text-lg font-semibold px-4 py-1">
                            Técnico(s) de Recojo
                        </span>
                    </div>

                    <div id="tecnicoFields" class="space-y-4">
                        <div class="tecnico-entry grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium">Nombre Técnico de Recojo</label>
                                <input type="text" name="nombreTecnicoEnvio[]" class="form-input w-full"
                                    placeholder="Ingrese el nombre">
                            </div>
                            <div>
                                <label class="block text-sm font-medium">DNI Técnico de Recojo</label>
                                <input type="text" name="dniTecnicoEnvio[]" class="form-input w-full"
                                    placeholder="Ingrese el DNI">
                            </div>
                        </div>
                    </div>

                    <!-- Botones alineados a la izquierda -->
                    <div class="mt-4 flex justify-start space-x-2">
                        <button type="button" id="agregarTecnico" class="btn btn-primary">
                            Agregar Técnico
                        </button>
                        <button type="button" id="eliminarUltimoTecnico" class="btn btn-danger hidden">
                            Eliminar último
                        </button>
                    </div>
                </div>




                <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const selectTienda = document.getElementById("idTienda");
                        const esEnvioContainer = document.getElementById("esEnvioContainer");
                        const esEnvioCheckbox = document.getElementById("esEnvio");
                        const selectTipoServicio = document.getElementById("tipoServicio"); // Selección de tipo de servicio
                        const tecnicoContainer = document.getElementById("tecnicoContainer");
                        const tecnicoDatosContainer = document.getElementById("tecnicoDatosContainer");
                        const tecnicoFields = document.getElementById("tecnicoFields");
                        const agregarTecnicoBtn = document.getElementById("agregarTecnico");
                        const eliminarTecnicoBtn = document.getElementById("eliminarUltimoTecnico");

                        if (!selectTienda || !esEnvioContainer || !esEnvioCheckbox || !tecnicoContainer || !
                            tecnicoDatosContainer || !selectTipoServicio) {
                            console.error("No se encontraron los elementos necesarios.");
                            return;
                        }


                        let selectsInicializados = false;

                        function verificarTipoServicio() {
    const tipoServicio = selectTipoServicio.options[selectTipoServicio.selectedIndex]?.value;
    const esEquipoContainer = document.getElementById("esEquipoContainer");
    const numeroCotizacionContainer = document.getElementById("numeroCotizacionContainer");

    if (parseInt(tipoServicio) === 6) {
        esEquipoContainer.classList.remove("hidden");

        if (!selectsInicializados) {
            esEquipoContainer.querySelectorAll('select.select2').forEach(function(select) {
                const prevNiceSelect = select.nextElementSibling;
                if (prevNiceSelect && prevNiceSelect.classList.contains('nice-select')) {
                    prevNiceSelect.remove();
                }
                NiceSelect.bind(select, { searchable: true });
                select.classList.add('hidden');
            });
            selectsInicializados = true;
        }
        console.log("✅ Mostrando esEquipoContainer (Tipo Servicio = 6)");
    } else {
        esEquipoContainer.classList.add("hidden");
        console.log("❌ Ocultando esEquipoContainer (Tipo Servicio ≠ 6)");
    }

    if (parseInt(tipoServicio) === 5) {
        numeroCotizacionContainer.style.display = "block";
        console.log("✅ Mostrando numeroCotizacionContainer (Tipo Servicio = 5)");
    } else {
        numeroCotizacionContainer.style.display = "none";
        console.log("❌ Ocultando numeroCotizacionContainer (Tipo Servicio ≠ 5)");
    }
}









                        function verificarEnvioContainer() {
                            const selectedDepartamento = selectTienda.options[selectTienda.selectedIndex]?.getAttribute(
                                "data-departamento");
                            const tipoServicio = selectTipoServicio.options[selectTipoServicio.selectedIndex]?.value;

                            console.log("Departamento:", selectedDepartamento, "| Tipo de Servicio:", tipoServicio);

                            // Convertir a número por si vienen como string
                            const dep = parseInt(selectedDepartamento);
                            const tipo = parseInt(tipoServicio);

                            // Condiciones según tu lógica
                            if (
                                (dep === 3926 && tipo === 1) || // Si es 3926 y tipo 1 => ocultar
                                (dep === 3926 && tipo === 2) || // Si es 3926 y tipo 2 => ocultar
                                (dep !== 3926 && tipo === 2) // Si es diferente a 3926 y tipo 2 => ocultar
                            ) {
                                esEnvioContainer.style.display = "none";
                                console.log("❌ Ocultando esEnvioContainer");
                            } else if (dep !== 3926 && tipo === 1) { // Si es diferente a 3926 y tipo 1 => mostrar
                                esEnvioContainer.style.display = "block";
                                console.log("✅ Mostrando esEnvioContainer");
                            }
                        }



                        function verificarEsEnvio() {
                            console.log("Verificando si es envío:", esEnvioCheckbox
                                .checked); // Debugging: Ver estado del checkbox
                            if (esEnvioCheckbox.checked) {
                                tecnicoContainer.style.display = "block";
                                tecnicoDatosContainer.style.display = "block";
                            } else {
                                tecnicoContainer.style.display = "none";
                                tecnicoDatosContainer.style.display = "none";
                            }
                        }

                        function actualizarBotonEliminar() {
                            const tecnicos = tecnicoFields.querySelectorAll(".tecnico-entry");
                            console.log("Número de técnicos:", tecnicos.length); // Debugging: Ver número de técnicos
                            if (tecnicos.length > 1) {
                                eliminarTecnicoBtn.classList.remove("hidden");
                            } else {
                                eliminarTecnicoBtn.classList.add("hidden");
                            }
                        }

                        function agregarTecnico() {
                            console.log("Agregando un nuevo técnico"); // Debugging: Cuando se agrega un nuevo técnico
                            const tecnicoEntry = document.createElement("div");
                            tecnicoEntry.classList.add("tecnico-entry", "grid", "grid-cols-1", "md:grid-cols-2", "gap-4");

                            tecnicoEntry.innerHTML = `
            <div>
                <label class="block text-sm font-medium">Nombre Técnico de Recojo</label>
                <input type="text" name="nombreTecnicoEnvio[]" class="form-input w-full" placeholder="Ingrese el nombre">
            </div>
            <div>
                <label class="block text-sm font-medium">DNI Técnico de Recojo</label>
                <input type="text" name="dniTecnicoEnvio[]" class="form-input w-full" placeholder="Ingrese el DNI">
            </div>
        `;

                            tecnicoFields.appendChild(tecnicoEntry);
                            actualizarBotonEliminar();
                        }

                        function eliminarUltimoTecnico() {
                            const tecnicos = tecnicoFields.querySelectorAll(".tecnico-entry");
                            console.log("Eliminando el último técnico. Técnicos actuales:", tecnicos
                                .length); // Debugging: Ver número de técnicos antes de eliminar
                            if (tecnicos.length > 1) {
                                tecnicos[tecnicos.length - 1].remove();
                                actualizarBotonEliminar();
                            }
                        }

                        // Mantener ocultos al inicio hasta que seleccione una tienda
                        esEnvioContainer.style.display = "none";
                        tecnicoContainer.style.display = "none";
                        tecnicoDatosContainer.style.display = "none";
                        verificarTipoServicio(); // Agrega esta línea para el estado inicial


                        // Eventos
                        selectTienda.addEventListener("change", function() {
                            console.log("Cambiando tienda."); // Debugging: Ver cuando se cambia la tienda
                            verificarEnvioContainer();
                            verificarEnvioContainer();

                        });
                        selectTipoServicio.addEventListener("change", function() {
                            console.log(
                                "Cambiando tipo de servicio."); // Debugging: Ver cuando se cambia el tipo de servicio
                            verificarEnvioContainer();

                            verificarTipoServicio(); // Agrega esta línea

                        });
                        esEnvioCheckbox.addEventListener("change", verificarEsEnvio);
                        agregarTecnicoBtn.addEventListener("click", agregarTecnico);
                        eliminarTecnicoBtn.addEventListener("click", eliminarUltimoTecnico);

                        actualizarBotonEliminar(); // Inicializa visibilidad del botón eliminar
                    });
                </script>


                <!-- Botones -->
                <div class="md:col-span-2 flex justify-end mt-4">
                    <a href="{{ route('ordenes.index') }}" class="btn btn-outline-danger">Cancelar</a>
                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>



    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializa Select2 solo una vez para otros selects si es necesario
            const allSelects = document.querySelectorAll('.select2');
            allSelects.forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });






            // Función para crear el errorText debajo de cada campo
            function createErrorText(inputId, message) {
                const input = document.getElementById(inputId);
                // Verificar si el mensaje de error ya existe para evitar duplicados
                if (!document.getElementById(`${inputId}Error`)) {
                    let errorText = document.createElement('p');
                    errorText.id = `${inputId}Error`;
                    errorText.classList.add('text-sm', 'text-red-500', 'mt-1');
                    errorText.textContent = message;
                    input.parentNode.appendChild(errorText);
                }
            }

            // Función para eliminar el errorText si el campo es completado
            function removeErrorText(inputId) {
                const errorText = document.getElementById(`${inputId}Error`);
                if (errorText) {
                    errorText.remove(); // Elimina el mensaje de error
                }
            }


            // Validación antes de enviar el formulario
            document.getElementById("ordenTrabajoForm").addEventListener("submit", function(event) {


                let isValid = true;

                // Obtener los valores de los campos
                const numeroTicket = document.getElementById("numero_ticket").value.trim();
                const idClienteGeneral = document.getElementById("idClienteGeneral").value;
                // const idTecnico = document.getElementById("idTecnico").value;
                const idCliente = document.getElementById("idCliente").value;
                const tipoServicio = document.getElementById("tipoServicio").value;
                const idTienda = document.getElementById("idTienda").value;
                const fallaReportada = document.getElementById("fallaReportada").value.trim();

                console.log("Valor del idCliente seleccionado:", idCliente); // Verifica el valor aquí


                // Verificar si algún campo está vacío
                if (!numeroTicket) {
                    isValid = false; // Si está vacío, no se puede enviar el formulario
                    createErrorText('numero_ticket', 'Campo ticket vacío');
                }

                if (!idClienteGeneral) {
                    isValid = false;
                    createErrorText('idClienteGeneral', 'Campo cliente general vacío');
                }

                if (!idCliente) {
                    isValid = false;
                    createErrorText('idCliente', 'Campo cliente vacío');
                }

                if (!tipoServicio) {
                    isValid = false;
                    createErrorText('tipoServicio', 'Campo servicio vacío');
                }

                if (!fallaReportada) {
                    isValid = false;
                    createErrorText('fallaReportada', 'Campo falla vacío');
                }

                // if (!idTecnico) {
                //     isValid = false;
                //     createErrorText('idTecnico', 'Campo tecnico vacío');
                // }

                if (!idTienda) {
                    isValid = false;
                    createErrorText('idTienda', 'Campo Tienda vacío');
                }

                // Si algún campo está vacío, mostrar mensaje global de Toastr
                if (!isValid) {
                    event.preventDefault(); // Evitar que el formulario se envíe
                    toastr.error('Por favor, complete todos los campos obligatorios.');
                }
            });

            // Agregar un event listener a cada campo para eliminar el error cuando se complete
            const fields = [
                'numero_ticket',
                'idClienteGeneral',
                'idCliente',
                'tipoServicio',
                'fallaReportada',
                'idTienda'
            ];

            fields.forEach(function(fieldId) {
                const field = document.getElementById(fieldId);
                field.addEventListener('input', function() {
                    if (field.value.trim() !== '') {
                        removeErrorText(
                            fieldId); // Eliminar el mensaje de error si el campo no está vacío
                    }
                });
            });

            // Para los selects, validar que el valor seleccionado no sea vacío (o el primer valor por defecto)
            const selectFields = [
                'idClienteGeneral',
                'idCliente',
                'tipoServicio',
                // 'idTecnico',
                'idTienda'
            ];

            selectFields.forEach(function(selectId) {
                const select = document.getElementById(selectId);
                select.addEventListener('change', function() {
                    if (select.value !== '') {
                        removeErrorText(
                            selectId
                        ); // Eliminar el mensaje de error si el select tiene una opción válida seleccionada
                    }
                });
            });



            // Lógica para obtener clientes relacionados con el Cliente General
            const selectClienteGeneral = document.getElementById('idClienteGeneral');
            const selectCliente = document.getElementById('idCliente');

            selectClienteGeneral.addEventListener('change', function() {
                const idClienteGeneral = this.value;

                // Limpiar las opciones del select de Cliente, pero no el select entero
                selectCliente.innerHTML = '<option value="" disabled selected>Seleccionar Cliente</option>';

                // Hacer la solicitud AJAX solo si se selecciona un Cliente General
                if (idClienteGeneral) {
                    fetch(`/clientes/${idClienteGeneral}`)
                        .then(response => response.json())
                        .then(data => {
                            // Asegúrate de que el array de clientes sea válido y tenga elementos
                            if (data.length > 0) {
                                // Iterar sobre los datos para agregar las opciones
                                data.forEach(cliente => {
                                    const option = document.createElement('option');
                                    option.value = cliente.idCliente;
                                    option.textContent =
                                        `${cliente.nombre} - ${cliente.documento}`;
                                    selectCliente.appendChild(option);
                                });
                            } else {
                                // Si no hay clientes relacionados, mostrar una opción vacía
                                const option = document.createElement('option');
                                option.value = '';
                                option.textContent = 'No hay clientes disponibles';
                                selectCliente.appendChild(option);
                            }
                        })
                        .catch(error => console.error('Error al obtener los clientes:', error));
                }
            });

            // Validación en tiempo real para el número de ticket
            document.getElementById('numero_ticket').addEventListener('input', function() {
                const inputTicket = document.getElementById('numero_ticket');
                const errorTicket = document.getElementById('errorTicket');
                const numero_ticketValue = inputTicket.value.trim();

                if (numero_ticketValue === "") {
                    inputTicket.classList.remove('border-red-500', 'border-green-500');
                    errorTicket.textContent = "Campo vacío"; // Mostrar mensaje de campo vacío
                    errorTicket.classList.remove('hidden');
                } else {
                    fetch(`/validar-ticket/${numero_ticketValue}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.existe) {
                                inputTicket.classList.add('border-red-500');
                                inputTicket.classList.remove('border-green-500');
                                errorTicket.textContent =
                                    'El número de ticket ya está en uso. Por favor, ingrese otro número.';
                                errorTicket.classList.remove('hidden');
                                // Mostrar toastr
                                toastr.error(
                                    'El número de ticket ya está en uso. Por favor, ingrese otro número.'
                                );
                            } else {
                                inputTicket.classList.remove('border-red-500');
                                inputTicket.classList.add('border-green-500');
                                errorTicket.classList.add('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error al verificar el ticket:', error);
                            inputTicket.classList.add('border-red-500');
                            errorTicket.textContent =
                                'Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.';
                            errorTicket.classList.remove('hidden');
                            // Mostrar toastr
                            toastr.error(
                                'Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.'
                            );
                        });
                }
            });

            // Validación cuando se intente enviar el formulario
            document.getElementById("ordenTrabajoForm").addEventListener("submit", function(event) {
                const numeroTicket = document.getElementById("numero_ticket").value.trim();

                // Validar que el número de ticket no esté vacío o no esté en uso
                if (!numeroTicket || document.getElementById('errorTicket').classList.contains('hidden') ===
                    false) {
                    event.preventDefault(); // Evitar que el formulario se envíe

                    // Mostrar mensaje de error si el número de ticket es vacío o ya está en uso
                    toastr.error('Por favor, ingrese un número de ticket válido.');
                }
            });

        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let clientesCargados = false; // Variable para verificar si los clientes ya fueron cargados
            let marcasCargadas = false; // Flag para verificar si las marcas ya han sido cargadas
            // let tiendasCargadas = false; // Flag para verificar si las tiendas ya han sido cargadas

            // Función para cargar los clientes
            function cargarClientes() {
                fetch('/clientesdatoscliente')
                    .then(response => response.json())
                    .then(data => {
                        console.log("🚀 Datos de clientes recibidos:",
                            data); // Aquí estamos haciendo el log de los datos recibidos

                        const select = document.getElementById(
                            'idCliente'); // Aquí aún usas 'idCliente' para obtener el select

                        // Puedes agregar un 'name' también, si lo deseas:
                        select.setAttribute('name', 'idCliente'); // Aquí le asignas el 'name' que quieres

                        // Vaciar y llenar el select con las opciones
                        select.innerHTML = '<option value="" disabled selected>Seleccionar Cliente</option>';
                        data.forEach(cliente => {
                            console.log(
                                `Cliente: ${cliente.nombre} - ${cliente.documento}`
                            ); // Mostrar el cliente en el log

                            const option = document.createElement('option');
                            option.value = cliente.idCliente;
                            option.textContent = `${cliente.nombre} - ${cliente.documento}`;
                            option.dataset.tienda = cliente.esTienda;
                            option.dataset.direccion = cliente.direccion; // <--- AÑADIDO
                            select.appendChild(option);
                        });

                        // Si ya existe una instancia previa de nice-select, la destruye
                        if (select.niceSelectInstance) {
                            select.niceSelectInstance.destroy();
                        }

                        // Inicializa nice-select y guarda la instancia en el select
                        select.niceSelectInstance = NiceSelect.bind(select, {
                            searchable: true
                        });

                        // Mostrar el select después de cargar los datos
                        select.style.display = 'block'; // O 'inline-block' según tu diseño
                        // ✅ Estilo para alinear verticalmente el texto
                        setTimeout(() => {
                            const nice = $(select).next(".nice-select");
                            nice.css({
                                'line-height': '2.2rem !important',
                                'height': '2.4rem',
                                'padding-top': '0.2rem',
                                'padding-bottom': '0.2rem'
                            });
                            nice.find('.current').css({
                                'line-height': '2.2rem !important',
                                'padding-top': '0 !important',
                                'padding-bottom': '0 !important'
                            });
                        }, 50);
                    })
                    .catch(error => {
                        console.error('Error al cargar clientes:', error);
                    });
            }

            // Función para obtener el cliente seleccionado
            function obtenerClienteSeleccionado() {
                const select = document.getElementById('idCliente');
                const idClienteSeleccionado = select.value; // Esto obtendrá el idCliente seleccionado
                console.log(`Cliente seleccionado: ${idClienteSeleccionado}`); // Ver el valor seleccionado

                if (idClienteSeleccionado) {
                    // Aquí puedes hacer algo con el idClienteSeleccionado
                    // Ejemplo: Hacer un fetch para obtener más detalles de ese cliente
                    console.log(`Detalles del cliente con ID: ${idClienteSeleccionado}`);
                } else {
                    console.log('No se ha seleccionado ningún cliente');
                }
            }

            // Evento de cambio en el select
            document.getElementById('idCliente').addEventListener('change', obtenerClienteSeleccionado);

            // Ocultar el select de clientes inicialmente
            let selectCliente = document.getElementById('idCliente');
            selectCliente.style.display = 'none'; // Esto oculta el primer select de "Cliente" al principio

            // Cargar los clientes solo si no se han cargado previamente
            if (!clientesCargados) {
                cargarClientes();
                clientesCargados = true;
            }








            $(document).ready(function() {
                console.log("🔹 DOM completamente cargado");

                // Elementos
                const clienteSelect = $("#idCliente");
                const tiendaSelectContainer = $(
                    "#selectTiendaContainer"); // Contenedor del select de tiendas
                const tiendaSelect = $("#idTienda"); // Select de tiendas

                // ✅ 🔥 Eliminamos cualquier clase o estilo raro en el select
                tiendaSelect.removeAttr("class style").addClass("form-input w-full");

                // Ocultar select de tiendas al inicio
                tiendaSelectContainer.hide();

                // 🔹 Escuchar cambios en el select de cliente
                clienteSelect.on("change", function() {
                    let clienteId = clienteSelect.val();

                    if (!clienteId) {
                        console.warn("⚠️ No se ha seleccionado un cliente.");
                        return;
                    }

                    console.log(`🔍 Cliente seleccionado: ${clienteId}`);

                    // Llamar a la API para obtener los datos del cliente y su tipo de documento
                    $.get(`/api/cliente/${clienteId}`, function(data) {
                        console.log("📌 Datos del cliente:", data);

                        // Establecer dirección automáticamente
                        $("#direccion").val(data.direccion || "");

                        // Verificamos el tipo de documento del cliente
                        if (data.idTipoDocumento == 8) {
                            // Si el cliente tiene idTipoDocumento == 2, traer todas las tiendas
                            console.log(
                                "🌍 Cliente con idTipoDocumento == 2, cargando todas las tiendas..."
                            );
                            mostrarSelectTiendas(clienteId,
                                true); // True para todas las tiendas
                        } else {
                            // Si el cliente tiene idTipoDocumento == 1, traer solo las tiendas relacionadas
                            console.log(
                                "🏪 Cliente con idTipoDocumento == 1, cargando tiendas relacionadas..."
                            );
                            mostrarSelectTiendas(clienteId,
                                false); // False para tiendas relacionadas
                        }

                    }).fail(function() {
                        console.error("❌ Error al obtener los datos del cliente.");
                    });
                });

                // Función para mostrar el select de tiendas
                function mostrarSelectTiendas(clienteId, cargarTodasTiendas) {
                    tiendaSelectContainer.show();
                    tiendaSelect.show();

                    tiendaSelect.empty().append(
                        '<option value="" selected disabled>Seleccionar Tienda</option>');

                    const urlTiendas = cargarTodasTiendas ?
                        `/api/tiendas` :
                        `/api/cliente/${clienteId}/tiendas`;

                    $.get(urlTiendas, function(data) {
                        console.log("🏪 Tiendas obtenidas:", data);

                        if (data.length > 0) {
                            data.forEach(tienda => {
                                tiendaSelect.append(`
                    <option value="${tienda.idTienda}" data-departamento="${tienda.departamento}">
                        ${tienda.nombre}
                    </option>
                `);
                            });
                        } else {
                            tiendaSelect.append(
                                '<option value="">No hay tiendas registradas</option>');
                        }

                        // Reemplazar el Nice Select anterior si existe
                        tiendaSelect.next(".nice-select").remove();
                        tiendaSelect.show(); // Mostrar original para inicializar

                        // Inicializar Nice Select 2 con buscador
                        NiceSelect.bind(tiendaSelect[0], {
                            searchable: true
                        });
                        tiendaSelect.hide(); // Ocultar el original

                        // Aplicar estilos solo al nice-select de tienda
                        setTimeout(() => {
                            const nice = tiendaSelect.next(".nice-select");
                            nice.css({
                                'height': '2.5rem',
                                'padding': '0 0.75rem',
                                'line-height': '2.5rem'
                            });
                            nice.find('.current').css({
                                'line-height': '2.5rem',
                                'padding': '0 !important'
                            });
                        }, 50);

                    }).fail(function() {
                        console.error("❌ Error al obtener tiendas.");
                    });
                }



                // Ejecutar la validación al cargar la página por si hay un cliente seleccionado
                if (clienteSelect.val()) {
                    clienteSelect.trigger("change");
                }
            });








            // Evento para cuando se selecciona un cliente
            document.getElementById('idCliente').addEventListener('change', function() {
                let clienteId = this.value;

                console.log('Cliente seleccionado:', clienteId);

                if (clienteId) {
                    console.log('Cliente seleccionado:',
                        clienteId); // Verificar si el cliente es seleccionado
                    fetch(`/clientes-generales/${clienteId}`)
                        .then(response => response.json())
                        .then(data => {
                            let select = document.getElementById('idClienteGeneral');
                            select.innerHTML =
                                '<option value="" selected>Seleccionar Cliente General</option>'; // Limpiar

                            // Verificar si se recibió algún dato
                            console.log('Clientes generales:',
                                data); // Verifica que se reciban los clientes generales

                            // Llenar el select con los clientes generales
                            data.forEach(clienteGeneral => {
                                let option = document.createElement('option');
                                option.value = clienteGeneral.idClienteGeneral;
                                option.textContent = clienteGeneral.descripcion;
                                select.appendChild(option);
                            });

                            // Si hay solo un cliente general, lo seleccionamos automáticamente
                            if (data.length === 1) {
                                select.value = data[0]
                                    .idClienteGeneral; // Seleccionar automáticamente el único cliente
                            }

                            // No inicializamos NiceSelect en el select de Cliente General
                            // Simplemente utilizamos el select estándar
                        })
                        .catch(error => console.error('Error al cargar clientes generales:', error));
                } else {
                    // Limpiar el select si no hay cliente seleccionado
                    document.getElementById('idClienteGeneral').innerHTML =
                        '<option value="" selected>Seleccionar Cliente General</option>';
                }

                console.log("Valor final del idCliente:", this.value);

            });
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>


    <script>
        $(document).ready(function() {

            $("#tipoProductoLab").change(function() {
                const idCategoria = $(this).val();
                const $marcaLab = $("#marcaLab");
                const $modeloLab = $("#modeloLab");

                if (idCategoria) {
                    $.ajax({
                        url: '/marcas/categoria/' + idCategoria,
                        method: 'GET',
                        success: function(response) {
                            // Limpiar y repoblar marcas
                            $marcaLab.empty().append(
                                '<option value="" disabled selected>Seleccionar Marca</option>'
                            );
                            response.forEach(function(marca) {
                                $marcaLab.append(
                                    `<option value="${marca.idMarca}">${marca.nombre}</option>`
                                );
                            });

                            // 🔁 Eliminar nice-select anterior en marca
                            const prevNiceMarca = $marcaLab.next('.nice-select');
                            if (prevNiceMarca.length) prevNiceMarca.remove();

                            // 🔁 Volver a aplicar nice-select
                            NiceSelect.bind($marcaLab[0], {
                                searchable: true
                            });
                            $marcaLab.addClass('hidden');
                            // Ajuste visual para centrar el texto del select
                            $marcaLab.next('.nice-select').css({
                                'display': 'flex',
                                'align-items': 'center',
                                'padding-top': '0.5rem',
                                'padding-bottom': '0.5rem'
                            });
                            // Limpiar modelo y ocultarlo
                            const prevNiceModelo = $modeloLab.next('.nice-select');
                            if (prevNiceModelo.length) prevNiceModelo.remove();

                            $modeloLab.empty().append(
                                '<option value="" disabled selected>Seleccionar Modelo</option>'
                            );
                            $modeloLab.addClass('hidden');
                        },
                        error: function() {
                            toastr.error('Error al cargar las marcas');
                        }
                    });
                } else {
                    const prevNiceMarca = $marcaLab.next('.nice-select');
                    if (prevNiceMarca.length) prevNiceMarca.remove();

                    const prevNiceModelo = $modeloLab.next('.nice-select');
                    if (prevNiceModelo.length) prevNiceModelo.remove();

                    $marcaLab.empty().addClass('hidden');
                    $modeloLab.empty().addClass('hidden');
                }
            });



            $("#marcaLab").change(function() {
                const idMarca = $(this).val();
                const idCategoria = $("#tipoProductoLab").val();
                const $modeloLab = $("#modeloLab");

                if (idMarca && idCategoria) {
                    $.ajax({
                        url: `/modelos/marca/${idMarca}/categoria/${idCategoria}`,
                        method: 'GET',
                        success: function(response) {
                            // Limpiar y cargar modelos
                            $modeloLab.empty().append(
                                '<option value="" disabled selected>Seleccionar Modelo</option>'
                            );
                            response.forEach(function(modelo) {
                                $modeloLab.append(
                                    `<option value="${modelo.idModelo}">${modelo.nombre}</option>`
                                );
                            });

                            // 🔁 Eliminar nice-select anterior en modelo
                            const prevNiceModelo = $modeloLab.next('.nice-select');
                            if (prevNiceModelo.length) prevNiceModelo.remove();

                            // 🔁 Volver a aplicar nice-select
                            NiceSelect.bind($modeloLab[0], {
                                searchable: true
                            });
                            $modeloLab.addClass('hidden');
                            // Ajuste visual para centrar el texto del select
                            $marcaLab.next('.nice-select').css({
                                'display': 'flex',
                                'align-items': 'center',
                                'padding-top': '0.5rem',
                                'padding-bottom': '0.5rem'
                            });
                        },
                        error: function() {
                            toastr.error('Error al cargar los modelos');
                        }
                    });
                } else {
                    const prevNiceModelo = $modeloLab.next('.nice-select');
                    if (prevNiceModelo.length) prevNiceModelo.remove();

                    $modeloLab.empty().addClass('hidden');
                }
            });
        });
    </script>
</x-layout.default>
