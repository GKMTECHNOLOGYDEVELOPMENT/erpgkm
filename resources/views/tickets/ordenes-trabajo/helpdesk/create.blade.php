<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

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
                    <select id="idCliente" name="idCliente" class="select2 w-full">
                        <option value="" disabled selected>Seleccionar Cliente</option>
                        <!-- Los clientes se cargarán dinámicamente aquí con JS -->
                    </select>
                </div>

                <!-- Cliente General -->
                <div>
                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full">
                        <option value="" selected>Seleccionar Cliente General</option>
                    </select>
                </div>


                <!-- Select para tiendas (solo si el cliente es tienda) -->
                <div id="selectTiendaContainer">
                    <label for="idTienda" class="block text-sm font-medium">Tienda</label>
                    <select id="idTienda" name="idTienda" class="select2 w-full">
                        <option value="">Seleccionar Tienda</option>
                    </select>
                </div>

                <!-- Tipo de Servicio -->
                <div>
                    <label for="tipoServicio" class="block text-sm font-medium">Tipo de Servicio</label>
                    <select id="tipoServicio" name="tipoServicio" class="select2 w-full">
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

                    <!-- Dentro del formulario -->
                    <div id="esEquipoContainer" class="col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 hidden">
                        <!-- Tipo Producto -->
                        <div>
                            <label class="block text-sm font-medium">Tipo Producto</label>
                            <select id="tipoProductoLab" name="tipoProducto" class="select2 w-full">

                                <option value="" disabled selected>Seleccionar Tipo de Producto</option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->idCategoria }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Marca -->
                        <div>
                            <label class="block text-sm font-medium">Marca</label>
                            <select id="marcaLab" name="marca" class="select2 w-full">
                                <option value="" disabled selected>Seleccionar Marca</option>
                                @foreach ($marcas as $marca)
                                    <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Modelo -->
                        <div>
                            <label class="block text-sm font-medium">Modelo</label>
                            <select id="modeloLab" name="modelo" class="select2 w-full hidden">
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
                        <select id="idTecnico" name="idTecnico" class="select2 w-full mb-2 hidden">
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

                <!-- Botones -->
                <div class="md:col-span-2 flex justify-end mt-4">
                    <a href="{{ route('ordenes.index') }}" class="btn btn-outline-danger">Cancelar</a>
                    <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script>
        $(document).ready(function() {
            const selectTienda = $('#idTienda');
            const selectTipoServicio = $('#tipoServicio');
            const esEnvioContainer = $('#esEnvioContainer');
            const esEnvioCheckbox = $('#esEnvio');
            const tecnicoContainer = $('#tecnicoContainer');
            const tecnicoDatosContainer = $('#tecnicoDatosContainer');
            const tecnicoFields = $('#tecnicoFields');
            const agregarTecnicoBtn = $('#agregarTecnico');
            const eliminarTecnicoBtn = $('#eliminarUltimoTecnico');

            let selectsInicializados = false;

            function verificarTipoServicio() {
                const tipoServicio = parseInt(selectTipoServicio.val());
                const esEquipoContainer = $('#esEquipoContainer');
                const numeroCotizacionContainer = $('#numeroCotizacionContainer');

                if (tipoServicio === 6) {
                    esEquipoContainer.removeClass('hidden');

                    if (!selectsInicializados) {
                        esEquipoContainer.find('select.select2').each(function() {
                            if ($(this).hasClass("select2-hidden-accessible")) {
                                $(this).select2('destroy');
                            }

                            $(this).removeClass('hidden').select2({
                                width: '100%'
                            });
                        });
                        selectsInicializados = true;
                    }
                    console.log("✅ Mostrando esEquipoContainer (Tipo Servicio = 6)");
                } else {
                    esEquipoContainer.addClass('hidden');
                    console.log("❌ Ocultando esEquipoContainer (Tipo Servicio ≠ 6)");
                }

                if (tipoServicio === 5) {
                    numeroCotizacionContainer.show();
                    console.log("✅ Mostrando numeroCotizacionContainer (Tipo Servicio = 5)");
                } else {
                    numeroCotizacionContainer.hide();
                    console.log("❌ Ocultando numeroCotizacionContainer (Tipo Servicio ≠ 5)");
                }
            }

            function verificarEnvioContainer() {
                const tipoServicio = parseInt(selectTipoServicio.val());
                const selectedDepartamento = selectTienda.find(':selected').data('departamento');

                const dep = parseInt(selectedDepartamento);
                const tipo = parseInt(tipoServicio);

                if (
                    (dep === 3926 && tipo === 1) ||
                    (dep === 3926 && tipo === 2) ||
                    (dep !== 3926 && tipo === 2)
                ) {
                    esEnvioContainer.hide();
                    console.log("❌ Ocultando esEnvioContainer");
                } else if (dep !== 3926 && tipo === 1) {
                    esEnvioContainer.show();
                    console.log("✅ Mostrando esEnvioContainer");
                }
            }

            function verificarEsEnvio() {
                console.log("Verificando si es envío:", esEnvioCheckbox.prop('checked'));
                if (esEnvioCheckbox.prop('checked')) {
                    tecnicoContainer.show();
                    tecnicoDatosContainer.show();
                } else {
                    tecnicoContainer.hide();
                    tecnicoDatosContainer.hide();
                }
            }

            function actualizarBotonEliminar() {
                const tecnicos = tecnicoFields.find('.tecnico-entry');
                if (tecnicos.length > 1) {
                    eliminarTecnicoBtn.removeClass('hidden');
                } else {
                    eliminarTecnicoBtn.addClass('hidden');
                }
            }

            function agregarTecnico() {
                const tecnicoEntry = $(`
                <div class="tecnico-entry grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Nombre Técnico de Recojo</label>
                        <input type="text" name="nombreTecnicoEnvio[]" class="form-input w-full" placeholder="Ingrese el nombre">
                    </div>
                    <div>
                        <label class="block text-sm font-medium">DNI Técnico de Recojo</label>
                        <input type="text" name="dniTecnicoEnvio[]" class="form-input w-full" placeholder="Ingrese el DNI">
                    </div>
                </div>
            `);

                tecnicoFields.append(tecnicoEntry);
                actualizarBotonEliminar();
            }

            function eliminarUltimoTecnico() {
                const tecnicos = tecnicoFields.find('.tecnico-entry');
                if (tecnicos.length > 1) {
                    tecnicos.last().remove();
                    actualizarBotonEliminar();
                }
            }

            // Eventos
            selectTienda.on("change", function() {
                console.log("Cambiando tienda.");
                verificarEnvioContainer();
            });

            selectTipoServicio.on("change", function() {
                console.log("Cambiando tipo de servicio.");
                verificarTipoServicio();
                verificarEnvioContainer();
            });

            esEnvioCheckbox.on("change", verificarEsEnvio);
            agregarTecnicoBtn.on("click", agregarTecnico);
            eliminarTecnicoBtn.on("click", eliminarUltimoTecnico);

            // Estado inicial
            esEnvioContainer.hide();
            tecnicoContainer.hide();
            tecnicoDatosContainer.hide();

            verificarTipoServicio();
            verificarEnvioContainer();
            actualizarBotonEliminar();
        });
    </script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar todos los selects Select2
            $('.select2').select2({
                width: '100%',
                placeholder: 'Seleccione una opción',
                allowClear: true
            });

            // Ejecutar lógica inicial si ya hay valor seleccionado
            const tipoServicioSelect = $('#tipoServicio');
            if (tipoServicioSelect.length && tipoServicioSelect.val()) {
                verificarTipoServicio();
                verificarEnvioContainer();
            }

            // Validación en tiempo real
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

            const setupFieldValidation = () => {
                const textFields = ['numero_ticket', 'fallaReportada'];
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

                const selectFields = ['idClienteGeneral', 'idCliente', 'tipoServicio', 'idTienda'];
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

            // Validación en tiempo real del ticket
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

            // Lógica para actualizar clientes al cambiar cliente general
            const setupClientesLogic = () => {
                const selectClienteGeneral = document.getElementById('idClienteGeneral');
                const selectCliente = document.getElementById('idCliente');

                if (!selectClienteGeneral || !selectCliente) return;

                selectClienteGeneral.addEventListener('change', function() {
                    const idClienteGeneral = this.value;
                    console.log('Cliente general seleccionado:', idClienteGeneral);

                    // Reset de opciones
                    $(selectCliente).empty().append(
                        new Option('Seleccionar Cliente', '', true, true)
                    ).trigger('change');

                    if (idClienteGeneral) {
                        fetch(`/clientes/${idClienteGeneral}`)
                            .then(response => {
                                if (!response.ok) throw new Error('Error en la respuesta');
                                return response.json();
                            })
                            .then(data => {
                                if (data?.length > 0) {
                                    data.forEach(cliente => {
                                        const newOption = new Option(
                                            `${cliente.nombre} - ${cliente.documento}`,
                                            cliente.idCliente,
                                            false,
                                            false
                                        );
                                        $(selectCliente).append(newOption);
                                    });
                                } else {
                                    $(selectCliente).append(new Option(
                                        'No hay clientes disponibles', '', true, true));
                                }
                                $(selectCliente).trigger('change');
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                $(selectCliente).append(new Option('Error al cargar clientes', '',
                                    true, true)).trigger('change');
                            });
                    }
                });
            };

            setupClientesLogic();

            // Validación al enviar formulario
            document.getElementById("ordenTrabajoForm")?.addEventListener("submit", function(event) {
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
            let clientesCargados = false;
            let marcasCargadas = false;

            function cargarClientes() {
                fetch('/clientesdatoscliente')
                    .then(response => response.json())
                    .then(data => {
                        console.log("🚀 Datos de clientes recibidos:", data);

                        let select = document.getElementById('idCliente');
                        if (!select) {
                            select = document.createElement('select');
                            select.id = 'idCliente';
                            select.name = 'idCliente';
                            document.querySelector('#contenedor-select-cliente').appendChild(select);
                        }

                        select.className = 'select2 w-full';
                        select.innerHTML = '<option value="" disabled selected>Seleccionar Cliente 2</option>';

                        data.forEach(cliente => {
                            const option = document.createElement('option');
                            option.value = cliente.idCliente;
                            option.textContent = `${cliente.nombre} - ${cliente.documento}`;
                            option.dataset.tienda = cliente.esTienda;
                            option.dataset.direccion = cliente.direccion;
                            select.appendChild(option);
                        });

                        // Inicializar Select2 (destruir si ya estaba)
                        if ($(select).hasClass('select2-hidden-accessible')) {
                            $(select).select2('destroy');
                        }

                        $(select).select2({
                            width: '100%',
                            placeholder: 'Seleccionar Cliente'
                        });

                        select.style.display = 'block';
                    })
                    .catch(error => {
                        console.error('Error al cargar clientes:', error);
                        toastr.error('Error al cargar la lista de clientes');
                    });
            }

            function obtenerClienteSeleccionado() {
                const select = document.getElementById('idCliente');
                const idClienteSeleccionado = select.value;
                console.log(`Cliente seleccionado: ${idClienteSeleccionado}`);
            }

            // Ocultar el select inicialmente
            const selectCliente = document.getElementById('idCliente');
            selectCliente.style.display = 'none';

            if (!clientesCargados) {
                cargarClientes();
                clientesCargados = true;
            }

            $(document).ready(function() {
                const clienteSelect = $("#idCliente");
                const tiendaSelect = $("#idTienda");
                const tiendaSelectContainer = $("#selectTiendaContainer");

                tiendaSelectContainer.hide();

                function mostrarSelectTiendas(clienteId, cargarTodasTiendas) {
                    tiendaSelectContainer.show();
                    tiendaSelect.empty().append(
                        '<option value="" selected disabled>Seleccionar Tienda</option>');

                    const urlTiendas = cargarTodasTiendas ?
                        `/api/tiendas` :
                        `/api/cliente/${clienteId}/tiendas`;

                    $.get(urlTiendas)
                        .done(function(data) {
                            console.log("🏪 Tiendas obtenidas:", data);
                            if (data.length > 0) {
                                data.forEach(tienda => {
                                    tiendaSelect.append(
                                        `<option value="${tienda.idTienda}" data-departamento="${tienda.departamento}">${tienda.nombre}</option>`
                                    );
                                });
                            } else {
                                tiendaSelect.append(
                                    '<option value="">No hay tiendas registradas</option>');
                            }

                            // Inicializar Select2 (destruir si ya estaba)
                            if (tiendaSelect.hasClass('select2-hidden-accessible')) {
                                tiendaSelect.select2('destroy');
                            }

                            tiendaSelect.select2({
                                width: '100%',
                                placeholder: 'Seleccionar Tienda'
                            });
                        })
                        .fail(function(error) {
                            console.error("❌ Error al obtener tiendas:", error);
                            tiendaSelect.append('<option value="">Error al cargar tiendas</option>');

                            if (tiendaSelect.hasClass('select2-hidden-accessible')) {
                                tiendaSelect.select2('destroy');
                            }

                            tiendaSelect.select2({
                                width: '100%',
                                placeholder: 'Seleccionar Tienda'
                            });
                        });
                }

                clienteSelect.on("change", function() {
                    const clienteId = $(this).val();
                    console.log(`🔍 Cliente seleccionado: ${clienteId}`);

                    if (!clienteId) {
                        tiendaSelectContainer.hide();
                        return;
                    }

                    $.get(`/api/cliente/${clienteId}`)
                        .done(function(data) {
                            console.log("📌 Datos del cliente:", data);
                            $("#direccion").val(data.direccion || "");

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

                    // Obtener y cargar clientes generales
                    fetch(`/clientes-generales/${clienteId}`)
                        .then(response => response.json())
                        .then(data => {
                            const select = document.getElementById('idClienteGeneral');
                            select.innerHTML =
                                '<option value="" selected>Seleccionar Cliente General</option>';

                            data.forEach(clienteGeneral => {
                                const option = document.createElement('option');
                                option.value = clienteGeneral.idClienteGeneral;
                                option.textContent = clienteGeneral.descripcion;
                                select.appendChild(option);
                            });

                            if (data.length === 1) {
                                select.value = data[0].idClienteGeneral;
                            }

                            // Reinicializar Select2 en cliente general
                            if ($(select).hasClass('select2-hidden-accessible')) {
                                $(select).select2('destroy');
                            }

                            $(select).select2({
                                width: '100%',
                                placeholder: 'Seleccionar Cliente General'
                            });
                        })
                        .catch(error => {
                            console.error('Error al cargar clientes generales:', error);
                        });
                });

                if (clienteSelect.val()) {
                    clienteSelect.trigger("change");
                }
            });

            // Evento adicional fuera del document.ready (si lo necesitas doble)
            document.getElementById('idCliente').addEventListener('change', obtenerClienteSeleccionado);
        });
    </script>


    <script>
        $(document).ready(function() {
            const $tipoProductoLab = $("#tipoProductoLab");
            const $marcaLab = $("#marcaLab");
            const $modeloLab = $("#modeloLab");

            // Inicializar select2 base
            $tipoProductoLab.select2({
                width: '100%',
                placeholder: "Seleccionar Tipo de Producto"
            });
            $marcaLab.select2({
                width: '100%',
                placeholder: "Seleccionar Marca"
            });
            $modeloLab.select2({
                width: '100%',
                placeholder: "Seleccionar Modelo"
            });

            // Al cambiar categoría
            $tipoProductoLab.change(function() {
                const idCategoria = $(this).val();

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

                            // Reinicializar Select2
                            $marcaLab.val(null).trigger('change');
                            $marcaLab.select2({
                                width: '100%',
                                placeholder: "Seleccionar Marca"
                            });

                            // Limpiar modelo
                            $modeloLab.empty().append(
                                '<option value="" disabled selected>Seleccionar Modelo</option>'
                            );
                            $modeloLab.val(null).trigger('change');
                            $modeloLab.select2({
                                width: '100%',
                                placeholder: "Seleccionar Modelo"
                            });
                        },
                        error: function() {
                            toastr.error('Error al cargar las marcas');
                        }
                    });
                } else {
                    $marcaLab.empty().select2({
                        width: '100%',
                        placeholder: "Seleccionar Marca"
                    });
                    $modeloLab.empty().select2({
                        width: '100%',
                        placeholder: "Seleccionar Modelo"
                    });
                }
            });

            // Al cambiar marca
            $marcaLab.change(function() {
                const idMarca = $(this).val();
                const idCategoria = $tipoProductoLab.val();

                if (idMarca && idCategoria) {
                    $.ajax({
                        url: `/modelos/marca/${idMarca}/categoria/${idCategoria}`,
                        method: 'GET',
                        success: function(response) {
                            $modeloLab.empty().append(
                                '<option value="" disabled selected>Seleccionar Modelo</option>'
                            );
                            response.forEach(function(modelo) {
                                $modeloLab.append(
                                    `<option value="${modelo.idModelo}">${modelo.nombre}</option>`
                                );
                            });

                            $modeloLab.val(null).trigger('change');
                            $modeloLab.select2({
                                width: '100%',
                                placeholder: "Seleccionar Modelo"
                            });
                        },
                        error: function() {
                            toastr.error('Error al cargar los modelos');
                        }
                    });
                } else {
                    $modeloLab.empty().select2({
                        width: '100%',
                        placeholder: "Seleccionar Modelo"
                    });
                }
            });
        });
    </script>

</x-layout.default>
