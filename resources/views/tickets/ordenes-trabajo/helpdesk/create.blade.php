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
                            <textarea id="observacionesRetirar" name="observaciones" class="form-textarea w-full"
                                rows="3" placeholder="Ingrese observaciones (opcional)"></textarea>
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
                        <input type="text" name="agencia" class="form-input w-full" placeholder="Ingrese el Agencia">
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
                        const selectTipoServicio = document.getElementById(
                            "tipoServicio"); // Selección de tipo de servicio
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
                            const tipoServicio = selectTipoServicio.options[selectTipoServicio.selectedIndex]
                                ?.value;
                            const esEquipoContainer = document.getElementById("esEquipoContainer");
                            const numeroCotizacionContainer = document.getElementById("numeroCotizacionContainer");

                            if (parseInt(tipoServicio) === 6) {
                                esEquipoContainer.classList.remove("hidden");

                                if (!selectsInicializados) {
                                    esEquipoContainer.querySelectorAll('select.select2').forEach(function(select) {
                                        const prevNiceSelect = select.nextElementSibling;
                                        if (prevNiceSelect && prevNiceSelect.classList.contains(
                                                'nice-select')) {
                                            prevNiceSelect.remove();
                                        }
                                        NiceSelect.bind(select, {
                                            searchable: true
                                        });
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
                            const selectedDepartamento = selectTienda.options[selectTienda.selectedIndex]
                                ?.getAttribute(
                                    "data-departamento");
                            const tipoServicio = selectTipoServicio.options[selectTipoServicio.selectedIndex]
                                ?.value;

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
                            console.log("Número de técnicos:", tecnicos
                                .length); // Debugging: Ver número de técnicos
                            if (tecnicos.length > 1) {
                                eliminarTecnicoBtn.classList.remove("hidden");
                            } else {
                                eliminarTecnicoBtn.classList.add("hidden");
                            }
                        }

                        function agregarTecnico() {
                            console.log(
                                "Agregando un nuevo técnico"); // Debugging: Cuando se agrega un nuevo técnico
                            const tecnicoEntry = document.createElement("div");
                            tecnicoEntry.classList.add("tecnico-entry", "grid", "grid-cols-1", "md:grid-cols-2",
                                "gap-4");

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
                                "Cambiando tipo de servicio."
                            ); // Debugging: Ver cuando se cambia el tipo de servicio
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
            // 1. Inicialización de NiceSelect con sincronización automática
            const initializeNiceSelect = (selectElement) => {
                if (selectElement.niceSelectInstance) {
                    selectElement.niceSelectInstance.destroy();
                }

                return NiceSelect.bind(selectElement, {
                    searchable: true,
                    callback: function(instance) {
                        // Sincronizar valor con el select original
                        selectElement.value = instance.selected.value;
                        console.log(`Select ${selectElement.id} cambiado a:`, selectElement.value);
                    }
                });
            };

            // Inicializar todos los selects con clase .select2
            document.querySelectorAll('.select2').forEach(select => {
                select.niceSelectInstance = initializeNiceSelect(select);
            });

            // 2. Funciones para manejo de errores
            const createErrorText = (inputId, message) => {
                const input = document.getElementById(inputId);
                if (!input) return;

                if (!document.getElementById(`${inputId}Error`)) {
                    const errorText = document.createElement('p');
                    errorText.id = `${inputId}Error`;
                    errorText.className = 'text-sm text-red-500 mt-1';
                    errorText.textContent = message;
                    input.parentNode.appendChild(errorText);
                }
            };

            const removeErrorText = (inputId) => {
                const errorText = document.getElementById(`${inputId}Error`);
                if (errorText) errorText.remove();
            };

            // 3. Validación en tiempo real para campos
            const setupFieldValidation = () => {
                // Campos de texto
                const textFields = [
                    'numero_ticket',
                    'fallaReportada'
                ];

                textFields.forEach(fieldId => {
                    const field = document.getElementById(fieldId);
                    if (field) {
                        field.addEventListener('input', function() {
                            if (this.value.trim() !== '') {
                                removeErrorText(fieldId);
                                this.classList.remove('border-red-500');
                                this.classList.add('border-green-500');
                            }
                        });
                    }
                });

                // Campos select
                const selectFields = [
                    'idClienteGeneral',
                    'idCliente',
                    'tipoServicio',
                    'idTienda'
                ];

                selectFields.forEach(selectId => {
                    const select = document.getElementById(selectId);
                    if (select) {
                        select.addEventListener('change', function() {
                            if (this.value !== '') {
                                removeErrorText(selectId);
                            }
                        });
                    }
                });
            };

            setupFieldValidation();

            // 4. Validación del número de ticket
            const validateTicket = () => {
                const inputTicket = document.getElementById('numero_ticket');
                const errorTicket = document.getElementById('errorTicket');

                if (!inputTicket) return;

                inputTicket.addEventListener('input', function() {
                    const value = this.value.trim();

                    if (value === "") {
                        this.classList.remove('border-green-500');
                        errorTicket.textContent = "Campo vacío";
                        errorTicket.classList.remove('hidden');
                        return;
                    }

                    fetch(`/validar-ticket/${value}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.existe) {
                                this.classList.add('border-red-500');
                                this.classList.remove('border-green-500');
                                errorTicket.textContent = 'Número de ticket ya en uso';
                                errorTicket.classList.remove('hidden');
                                toastr.error('Número de ticket ya en uso');
                            } else {
                                this.classList.remove('border-red-500');
                                this.classList.add('border-green-500');
                                errorTicket.classList.add('hidden');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            this.classList.add('border-red-500');
                            errorTicket.textContent = 'Error al verificar ticket';
                            errorTicket.classList.remove('hidden');
                            toastr.error('Error al verificar ticket');
                        });
                });
            };

            validateTicket();

            // 5. Lógica para clientes y clientes generales
            const setupClientesLogic = () => {
                const selectClienteGeneral = document.getElementById('idClienteGeneral');
                const selectCliente = document.getElementById('idCliente');

                if (!selectClienteGeneral || !selectCliente) return;

                selectClienteGeneral.addEventListener('change', function() {
                    const idClienteGeneral = this.value;
                    console.log('Cliente general seleccionado:', idClienteGeneral);

                    // Reiniciar select de cliente
                    selectCliente.innerHTML =
                        '<option value="" disabled selected>Seleccionar Cliente</option>';

                    if (idClienteGeneral) {
                        fetch(`/clientes/${idClienteGeneral}`)
                            .then(response => {
                                if (!response.ok) throw new Error('Error en la respuesta');
                                return response.json();
                            })
                            .then(data => {
                                if (data?.length > 0) {
                                    data.forEach(cliente => {
                                        const option = new Option(
                                            `${cliente.nombre} - ${cliente.documento}`,
                                            cliente.idCliente
                                        );
                                        selectCliente.add(option);
                                    });

                                    // Re-inicializar NiceSelect para mantener sincronización
                                    selectCliente.niceSelectInstance = initializeNiceSelect(
                                        selectCliente);
                                } else {
                                    selectCliente.add(new Option('No hay clientes disponibles',
                                        ''));
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                selectCliente.add(new Option('Error al cargar clientes', ''));
                            });
                    }
                });
            };

            setupClientesLogic();

            // 6. Validación final del formulario
            document.getElementById("ordenTrabajoForm")?.addEventListener("submit", function(event) {
                // Sincronizar valores de NiceSelect antes de validar
                document.querySelectorAll('.select2').forEach(select => {
                    const niceSelect = select.nextElementSibling;
                    if (niceSelect?.classList.contains('nice-select')) {
                        const selected = niceSelect.querySelector('.option.selected');
                        if (selected) select.value = selected.dataset.value;
                    }
                });

                // Validar campos requeridos
                const camposRequeridos = {
                    'numero_ticket': 'Campo ticket vacío',
                    'idClienteGeneral': 'Campo cliente general vacío',
                    'idCliente': 'Campo cliente vacío',
                    'tipoServicio': 'Campo servicio vacío',
                    'fallaReportada': 'Campo falla vacío',
                    'idTienda': 'Campo Tienda vacío'
                };

                let isValid = true;
                Object.entries(camposRequeridos).forEach(([id, mensaje]) => {
                    const campo = document.getElementById(id);
                    if (!campo || !campo.value.trim()) {
                        isValid = false;
                        createErrorText(id, mensaje);
                        console.error(`Campo requerido vacío: ${id}`);
                    }
                });

                // Validación especial del ticket
                const ticketError = document.getElementById('errorTicket');
                if (!document.getElementById('numero_ticket').value.trim() ||
                    (ticketError && !ticketError.classList.contains('hidden'))) {
                    isValid = false;
                    toastr.error('Por favor, ingrese un número de ticket válido.');
                }

                if (!isValid) {
                    event.preventDefault();
                    toastr.error('Complete todos los campos obligatorios');
                } else {
                    console.log('Formulario válido. Enviando...');
                    console.log('idCliente:', document.getElementById('idCliente').value);
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
                        console.log("🚀 Datos de clientes recibidos:", data);

                        // Obtener o crear el select
                        let select = document.getElementById('idCliente');

                        // Si no existe, lo creamos
                        if (!select) {
                            select = document.createElement('select');
                            select.id = 'idCliente';
                            select.name = 'idCliente';
                            select.className = 'form-input w-full';
                            // Agregarlo al DOM donde corresponda
                            document.querySelector('#contenedor-select-cliente').appendChild(select);
                        }

                        // Configurar atributos (por si acaso)
                        select.id = 'idCliente'; // Asegurar el id
                        select.name = 'idCliente'; // Asegurar el name
                        select.setAttribute('data-test', 'select-cliente'); // Atributo adicional para testing

                        // Vaciar y llenar el select con las opciones
                        select.innerHTML = '<option value="" disabled selected>Seleccionar Cliente 2</option>';

                        data.forEach(cliente => {
                            console.log(`Cliente: ${cliente.nombre} - ${cliente.documento}`);

                            const option = document.createElement('option');
                            option.value = cliente.idCliente;
                            option.textContent = `${cliente.nombre} - ${cliente.documento}`;
                            option.dataset.tienda = cliente.esTienda;
                            option.dataset.direccion = cliente.direccion;
                            select.appendChild(option);
                        });

                        // Manejo de NiceSelect
                        if (select.niceSelectInstance) {
                            select.niceSelectInstance.destroy();
                        }

                        // Inicializar NiceSelect con configuración mejorada
                        select.niceSelectInstance = NiceSelect.bind(select, {
                            searchable: true,
                            placeholder: 'Seleccionar Cliente',
                            callback: function(select) {
                                // Sincronizar el valor con el select original
                                document.getElementById('idCliente').value = select.value;
                            }
                        });

                        // Estilos para NiceSelect
                        select.style.display = 'block';
                        setTimeout(() => {
                            const nice = $(select).next(".nice-select");
                            if (nice.length) {
                                nice.css({
                                    'line-height': '2.2rem',
                                    'height': '2.4rem',
                                    'padding-top': '0.2rem',
                                    'padding-bottom': '0.2rem',
                                    'display': 'flex',
                                    'align-items': 'center'
                                });
                                nice.find('.current').css({
                                    'line-height': '2.2rem',
                                    'padding-top': '0',
                                    'padding-bottom': '0'
                                });
                            }
                        }, 50);

                    })
                    .catch(error => {
                        console.error('Error al cargar clientes:', error);
                        toastr.error('Error al cargar la lista de clientes');
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

                // 1. Configuración inicial del select de Cliente
                const clienteSelect = $("#idCliente")
                    .attr('id', 'idCliente') // Asegurar id
                    .attr('name', 'idCliente') // Asegurar name
                    .removeAttr("class style")
                    .addClass("form-input w-full");

                // 2. Configuración inicial del select de Tienda
                const tiendaSelectContainer = $("#selectTiendaContainer");
                const tiendaSelect = $("#idTienda")
                    .attr('id', 'idTienda') // Asegurar id
                    .attr('name', 'idTienda') // Asegurar name
                    .removeAttr("class style")
                    .addClass("form-input w-full");

                // Ocultar select de tiendas al inicio
                tiendaSelectContainer.hide();

                // 3. Función mejorada para mostrar tiendas
                function mostrarSelectTiendas(clienteId, cargarTodasTiendas) {
                    tiendaSelectContainer.show();

                    // Asegurar atributos antes de llenar
                    tiendaSelect.attr('id', 'idTienda')
                        .attr('name', 'idTienda')
                        .empty()
                        .append('<option value="" selected disabled>Seleccionar Tienda</option>');

                    const urlTiendas = cargarTodasTiendas ? `/api/tiendas` :
                        `/api/cliente/${clienteId}/tiendas`;

                    $.get(urlTiendas)
                        .done(function(data) {
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

                            // Inicializar NiceSelect con sincronización
                            inicializarNiceSelectTienda();
                        })
                        .fail(function(error) {
                            console.error("❌ Error al obtener tiendas:", error);
                            tiendaSelect.append('<option value="">Error al cargar tiendas</option>');
                            inicializarNiceSelectTienda();
                        });
                }

                // 4. Función para inicializar NiceSelect en tienda
                function inicializarNiceSelectTienda() {
                    // Destruir instancia previa si existe
                    if (tiendaSelect[0].niceSelectInstance) {
                        tiendaSelect[0].niceSelectInstance.destroy();
                    }

                    // Mostrar select original para inicialización
                    tiendaSelect.show();

                    // Inicializar NiceSelect con sincronización
                    tiendaSelect[0].niceSelectInstance = NiceSelect.bind(tiendaSelect[0], {
                        searchable: true,
                        placeholder: 'Seleccionar Tienda',
                        callback: function(select) {
                            // Sincronizar valor con el select original
                            document.getElementById('idTienda').value = select.value;
                        }
                    });

                    // Ocultar select original
                    tiendaSelect.hide();

                    // Aplicar estilos
                    setTimeout(() => {
                        const nice = tiendaSelect.next(".nice-select");
                        if (nice.length) {
                            nice.css({
                                'height': '2.5rem',
                                'padding': '0 0.75rem',
                                'line-height': '2.5rem',
                                'display': 'flex',
                                'align-items': 'center'
                            });
                            nice.find('.current').css({
                                'line-height': '2.5rem',
                                'padding': '0 !important'
                            });
                        }
                    }, 50);
                }

                // 5. Evento change para cliente (con validación de atributos)
                clienteSelect.on("change", function() {
                    // Asegurar atributos en cada cambio
                    $(this).attr('id', 'idCliente').attr('name', 'idCliente');

                    const clienteId = $(this).val();
                    console.log(`🔍 Cliente seleccionado: ${clienteId}`);

                    if (!clienteId) {
                        console.warn("⚠️ No se ha seleccionado un cliente.");
                        tiendaSelectContainer.hide();
                        return;
                    }

                    $.get(`/api/cliente/${clienteId}`)
                        .done(function(data) {
                            console.log("📌 Datos del cliente:", data);
                            $("#direccion").val(data.direccion || "");

                            // Verificar tipo de documento
                            if (data.idTipoDocumento == 8) {
                                console.log(
                                    "🌍 Cliente con idTipoDocumento == 8, cargando todas las tiendas..."
                                );
                                mostrarSelectTiendas(clienteId, true);
                            } else {
                                console.log(
                                    "🏪 Cliente con idTipoDocumento == 1, cargando tiendas relacionadas..."
                                );
                                mostrarSelectTiendas(clienteId, false);
                            }
                        })
                        .fail(function(error) {
                            console.error("❌ Error al obtener datos del cliente:", error);
                        });
                });

                // 6. Ejecutar validación inicial si hay cliente seleccionado
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