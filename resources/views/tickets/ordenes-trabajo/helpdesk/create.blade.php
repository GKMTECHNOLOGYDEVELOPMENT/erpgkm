<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
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

                <!-- Cliente General -->
                <div>
                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full" style="display: none;">
                        <option value="" disabled selected>Seleccionar Cliente General</option>
                        @foreach ($clientesGenerales as $cliente)
                        <option value="{{ $cliente->idClienteGeneral }}">{{ $cliente->descripcion }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cliente -->
                <div>
                    <label for="idCliente" class="block text-sm font-medium">Cliente</label>
                    <select id="idCliente" name="idCliente" class="form-input w-full">
                        <option value="" disabled selected>Seleccionar Cliente</option>
                        <!-- Los clientes se cargarán dinámicamente aquí con JS -->
                    </select>
                </div>

        

                <!-- Tienda -->
<div>
    <label for="idTienda" class="block text-sm font-medium">Tienda</label>
    <select id="idTienda" name="idTienda" class="select2 w-full">
        <option value="" disabled selected>Seleccionar Tienda</option>
        @foreach ($tiendas as $tienda)
        <option value="{{ $tienda->idTienda }}" data-departamento="{{ $tienda->departamento }}">
            {{ $tienda->nombre }}
        </option>
        @endforeach
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



                <!-- Falla Reportada -->
                <div class="">
                    <label for="fallaReportada" class="block text-sm font-medium">Falla Reportada</label>
                    <textarea id="fallaReportada" name="fallaReportada" rows="3" class="form-input w-full"
                        placeholder="Describa la falla reportada"></textarea>
                </div>

                <div id="esRecojoContainer" class="hidden">
                    <label class="block text-sm font-medium mb-2">¿Es Recojo?</label>
                    <label class="w-12 h-6 relative inline-block">
                        <input type="checkbox" id="esRecojo" name="esRecojo" class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
                        <span class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                    </label>
                </div>



      <!-- Es Envío (inicialmente oculto) -->
<div id="esEnvioContainer" style="display: none;">
    <label class="block text-sm font-medium mb-2">¿Es Envio?</label>
    <label class="w-12 h-6 relative inline-block">
        <input type="hidden" name="esEnvio" value="0">
        <input type="checkbox" id="esEnvio" name="esEnvio" value="1" class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer" />
        <span class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
    </label>
</div>

<!-- Técnico (inicialmente oculto) -->
<div id="tecnicoContainer" style="display: none;">
    <label for="idTecnico" class="block text-sm font-medium">Técnico Envío</label>
    <select id="idTecnico" name="idTecnico" class="select2 w-full">
        <option value="" disabled selected>Seleccionar Técnico</option>
        @foreach ($usuarios as $usuario)
        <option value="{{ $usuario->idUsuario }}">{{ $usuario->Nombre }}</option>
        @endforeach
    </select>

  <!-- Tipo de Recojo -->
<label for="tipoRecojo" class="block text-sm font-medium mt-4">Tipo de Recojo</label>
<select id="tipoRecojo" name="tipoRecojo" class="select2 w-full">
    <option value="" disabled selected>Seleccionar Tipo de Recojo</option>
    @foreach ($tiposRecojo as $tipo)
        <option value="{{ $tipo->idtipoRecojo }}">{{ $tipo->nombre }}</option>
    @endforeach
</select>


   <!-- Tipo de Envío -->
    <label for="tipoEnvio" class="block text-sm font-medium mt-4">Tipo de Envío</label>
    <select id="tipoEnvio" name="tipoEnvio" class="select2 w-full">
        <option value="" disabled selected>Seleccionar Tipo de Envío</option>
        @foreach($tiposEnvio as $tipoEnvio)
            <option value="{{ $tipoEnvio->idtipoenvio }}">{{ $tipoEnvio->nombre }}</option>
        @endforeach
    </select>

</div>


<!-- Datos del Técnico de Envío (inicialmente oculto) -->
<div id="tecnicoDatosContainer" style="display: none;">
    <div id="tecnicoFields">
        <div class="tecnico-entry">
            <label class="block text-sm font-medium">Nombre Técnico de Recojo</label>
            <input type="text" name="nombreTecnicoEnvio[]" class="w-full border rounded p-2 mb-2" placeholder="Ingrese el nombre">

            <label class="block text-sm font-medium">DNI Técnico de Recojo</label>
            <input type="text" name="dniTecnicoEnvio[]" class="w-full border rounded p-2 mb-2" placeholder="Ingrese el DNI">
        </div>
    </div>

    <!-- Botones para agregar o quitar técnicos -->
    <button type="button" id="agregarTecnico" class="bg-blue-500 text-white px-4 py-2 rounded mt-2">Agregar Técnico</button>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const selectTienda = document.getElementById("idTienda");
    const esEnvioContainer = document.getElementById("esEnvioContainer");
    const esEnvioCheckbox = document.getElementById("esEnvio");
    const tecnicoContainer = document.getElementById("tecnicoContainer");
    const tecnicoDatosContainer = document.getElementById("tecnicoDatosContainer");
    const tecnicoFields = document.getElementById("tecnicoFields");
    const agregarTecnicoBtn = document.getElementById("agregarTecnico");

    if (!selectTienda || !esEnvioContainer || !esEnvioCheckbox || !tecnicoContainer || !tecnicoDatosContainer) {
        console.error("No se encontraron los elementos necesarios.");
        return;
    }

    function verificarDepartamento() {
        const selectedOption = selectTienda.options[selectTienda.selectedIndex];
        const departamento = selectedOption.getAttribute("data-departamento");

        if (departamento === "3926") {
            esEnvioContainer.style.display = "none";
            tecnicoContainer.style.display = "none";
            tecnicoDatosContainer.style.display = "none";
        } else {
            esEnvioContainer.style.display = "block";
        }
    }

    function verificarEsEnvio() {
        if (esEnvioCheckbox.checked) {
            tecnicoContainer.style.display = "block";
            tecnicoDatosContainer.style.display = "block";
        } else {
            tecnicoContainer.style.display = "none";
            tecnicoDatosContainer.style.display = "none";
        }
    }

    function agregarTecnico() {
        const tecnicoEntry = document.createElement("div");
        tecnicoEntry.classList.add("tecnico-entry");

        tecnicoEntry.innerHTML = `
            <label class="block text-sm font-medium">Nombre Técnico de Recojo</label>
            <input type="text" name="nombreTecnicoEnvio[]" class="w-full border rounded p-2 mb-2" placeholder="Ingrese el nombre">

            <label class="block text-sm font-medium">DNI Técnico de Recojo</label>
            <input type="text" name="dniTecnicoEnvio[]" class="w-full border rounded p-2 mb-2" placeholder="Ingrese el DNI">

            <button type="button" class="eliminarTecnico bg-red-500 text-white px-3 py-1 rounded mt-2">Eliminar</button>
        `;

        tecnicoFields.appendChild(tecnicoEntry);

        // Evento para eliminar el técnico
        tecnicoEntry.querySelector(".eliminarTecnico").addEventListener("click", function () {
            tecnicoEntry.remove();
        });
    }

    // Mantener ocultos al inicio hasta que seleccione una tienda
    esEnvioContainer.style.display = "none";
    tecnicoContainer.style.display = "none";
    tecnicoDatosContainer.style.display = "none";

    // Eventos
    selectTienda.addEventListener("change", verificarDepartamento);
    esEnvioCheckbox.addEventListener("change", verificarEsEnvio);
    agregarTecnicoBtn.addEventListener("click", agregarTecnico);
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

        // Lógica para mostrar el checkbox si el tipo de servicio es "SOPORTE ON SITE"
        const selectTipoServicio = document.getElementById("tipoServicio");
        const esRecojoContainer = document.getElementById("esRecojoContainer");

        function verificarTipoServicio() {
            const tipoSeleccionado = selectTipoServicio.options[selectTipoServicio.selectedIndex];
            const nombreTipo = tipoSeleccionado ? tipoSeleccionado.dataset.nombre : "";

            if (nombreTipo === "SOPORTE ON SITE") {
                esRecojoContainer.classList.remove("hidden");
            } else {
                esRecojoContainer.classList.add("hidden");
            }
        }

        // Evento de cambio en el select
        selectTipoServicio.addEventListener("change", verificarTipoServicio);

        // Verificar al cargar la página
        verificarTipoServicio();




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

        // Verificar si algún campo está vacío
        if (!numeroTicket) {
            isValid = false;  // Si está vacío, no se puede enviar el formulario
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
        // 'idTecnico',
        'idTienda'
    ];

    fields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);
        field.addEventListener('input', function() {
            if (field.value.trim() !== '') {
                removeErrorText(fieldId); // Eliminar el mensaje de error si el campo no está vacío
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
                removeErrorText(selectId); // Eliminar el mensaje de error si el select tiene una opción válida seleccionada
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
                                option.textContent = `${cliente.nombre} - ${cliente.documento}`;
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
                            errorTicket.textContent = 'El número de ticket ya está en uso. Por favor, ingrese otro número.';
                            errorTicket.classList.remove('hidden');
                            // Mostrar toastr
                            toastr.error('El número de ticket ya está en uso. Por favor, ingrese otro número.');
                        } else {
                            inputTicket.classList.remove('border-red-500');
                            inputTicket.classList.add('border-green-500');
                            errorTicket.classList.add('hidden');
                        }
                    })
                    .catch(error => {
                        console.error('Error al verificar el ticket:', error);
                        inputTicket.classList.add('border-red-500');
                        errorTicket.textContent = 'Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.';
                        errorTicket.classList.remove('hidden');
                        // Mostrar toastr
                        toastr.error('Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.');
                    });
            }
        });

        // Validación cuando se intente enviar el formulario
        document.getElementById("ordenTrabajoForm").addEventListener("submit", function(event) {
            const numeroTicket = document.getElementById("numero_ticket").value.trim();

            // Validar que el número de ticket no esté vacío o no esté en uso
            if (!numeroTicket || document.getElementById('errorTicket').classList.contains('hidden') === false) {
                event.preventDefault(); // Evitar que el formulario se envíe

                // Mostrar mensaje de error si el número de ticket es vacío o ya está en uso
                toastr.error('Por favor, ingrese un número de ticket válido.');
            }
        });

    });
</script>



<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>

</x-layout.default>