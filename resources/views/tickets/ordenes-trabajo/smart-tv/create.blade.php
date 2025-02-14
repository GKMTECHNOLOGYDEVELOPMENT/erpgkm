<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">


    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }

        #map {
            height: 300px;
            width: 100%;
        }
    </style>

    <!-- Breadcrumb -->
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

  


    <!-- Contenedor principal -->
    <div x-data="{ openClienteModal: false }" class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Orden de Trabajo</h2>



        <div class="p-5">
            <form id="ordenTrabajoForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('ordenes.storesmart') }}">
                @csrf

              

                <!-- Cliente: Seleccionar o crear nuevo -->
                <div class="col-span-1">
                    <div class="flex items-center space-x-2">
                        <label for="idCliente" class="block text-sm font-medium">Usuario Final</label>
                        <button type="button" class="btn btn-primary p-1 mb-2" @click="openClienteModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <!-- Se usa nice-select2 (clase select2) -->
                    <select id="idCliente" name="idCliente">
                    </select>
                </div>

                
                <!-- Cliente General -->
                <div>
                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                    <select id="idClienteGeneral" name="idClienteGeneral" class="form-input w-full">
                        <option value="" selected>Seleccionar Cliente General</option>
                    </select>                   
                </div>

                <!-- Número de Ticket -->
                <div>
                    <label for="nroTicket" class="block text-sm font-medium">N. Ticket</label>
                    <input id="nroTicket" name="nroTicket" type="text" class="form-input w-full"
                        placeholder="Ingrese el número de ticket">
                        <p id="errorTicket" class="text-sm text-red-500 mt-2 hidden"></p>
                        </div>




                <!-- Tienda (usa nice-select2) -->
                <div>
                    <label for="idTienda" class="block text-sm font-medium">Tienda</label>
                    <select id="idTienda" name="idTienda" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Tienda</option>
                        @foreach ($tiendas as $tienda)
                            <option value="{{ $tienda->idTienda }}">{{ $tienda->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Dirección -->
                <div>
                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                    <input id="direccion" type="text" name="direccion" class="form-input w-full"
                        placeholder="Ingrese la dirección">
                </div>

                <!-- Fecha de Compra -->
                <div>
                    <label for="fechaCompra" class="block text-sm font-medium">Fecha de Compra</label>
                    <input id="fechaCompra" name="fechaCompra" type="date" class="form-input w-full"
                        placeholder="Seleccionar fecha">
                </div>

                <!-- Marca -->
                <div>
                    <label for="idMarca" class="block text-sm font-medium">Marca</label>
                    <select id="idMarca" name="idMarca" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Modelo -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium">Modelo</label>
                    <select id="idModelo" name="idModelo" class="form-input w-full">
                        <option value="" selected>Seleccionar Modelo</option>
                    </select>
                </div>

                <!-- Serie -->
                <div>
                    <label for="serie" class="block text-sm font-medium">N. Serie</label>
                    <input id="serie" name="serie" type="text" class="form-input w-full"
                        placeholder="Ingrese la serie">
                </div>

              
<!-- Selección de Tickets -->
<div id="ticketSelectionContainer">
    <label for="selectTickets" class="block text-sm font-medium">Seleccionar Ticket</label>
    <select id="selectTickets" name="selectTickets" class="form-input w-full" style="display: none;">
        <option value="" selected>Seleccionar Ticket</option>
    </select>
</div>




                <!-- Falla Reportada -->
                <div class="">
                    <label for="fallaReportada" class="block text-sm font-medium">Falla Reportada</label>
                    <textarea id="fallaReportada" name="fallaReportada" rows="1" class="form-input w-full"
                        placeholder="Describa la falla reportada"></textarea>
                </div>

                <!-- Latitud -->
                <div>
                    <label for="latitud" class="block text-sm font-medium">Latitud</label>
                    <input id="latitud" type="text" name="lat" class="form-input w-full"
                        placeholder="Latitud" readonly>
                </div>

                <!-- Longitud -->
                <div>
                    <label for="longitud" class="block text-sm font-medium">Longitud</label>
                    <input id="longitud" type="text" name="lng" class="form-input w-full"
                        placeholder="Longitud" readonly>
                </div>

                <!-- Mapa -->
                <div class="col-span-1 md:col-span-2">
                    <label class="block text-sm font-medium">Mapa</label>
                    <div id="map" class="w-full h-64 rounded border"></div>
                </div>

                <!-- Botones -->
                <div class="col-span-1 md:col-span-2 flex justify-end mt-4 gap-2">
                    <a href="{{ route('ordenes.index') }}" class="btn btn-outline-danger">Cancelar</a>
                    <button type="submit" id="btnGuardar" class="btn btn-primary ml-4">Guardar</button>
                </div>
            </form>
        </div>




        <!-- Modal para crear nuevo Cliente (opcional) -->
        <div x-show="openClienteModal" class="fixed inset-0 bg-black/60 z-[999] overflow-y-auto"
            style="display: none;" @click.self="openClienteModal = false">
            <div class="flex items-start justify-center min-h-screen px-4">
                <div x-show="openClienteModal" x-transition.duration.300
                    class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-3xl my-8">
                    <!-- Header del Modal -->
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">Agregar Cliente</h5>
                        <button type="button" class="text-white-dark hover:text-dark"
                            @click="openClienteModal = false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="w-6 h-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="modal-scroll">
                        <!-- Formulario para nuevo Cliente -->
                        <!-- Formulario -->
                        <form class="p-5 space-y-4" id="clienteForm" method="POST" enctype="multipart/form-data">
                            @csrf <!-- Asegúrate de incluir el token CSRF -->

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- ClienteGeneral -->
                                <div>
                                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente
                                        General</label>
                                    <select id="idClienteGeneraloption" name="idClienteGeneraloption[]"
                                        placeholder="Seleccionar Cliente General" multiple style="display:none">
                                        @foreach ($clientesGenerales as $clienteGeneral)
                                            <option value="{{ $clienteGeneral->idClienteGeneral }}">
                                                {{ $clienteGeneral->descripcion }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Contenedor para mostrar los seleccionados -->
                                <div id="selected-items-container">
                                    <strong>Seleccionados:</strong>
                                    <div id="selected-items-list" class="flex flex-wrap gap-2"></div>
                                </div>

                                <!-- Nombre -->
                                <div>
                                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                                    <input id="nombre" type="text" name="nombre" class="form-input w-full"
                                        placeholder="Ingrese el nombre">
                                </div>
                                <!-- Tipo Documento -->
                                <div>
                                    <label for="idTipoDocumento" class="block text-sm font-medium">Tipo
                                        Documento</label>
                                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full"
                                        style="display:none">
                                        <option value="" disabled selected>Seleccionar Tipo Documento</option>
                                        @foreach ($tiposDocumento as $tipoDocumento)
                                            <option value="{{ $tipoDocumento->idTipoDocumento }}">
                                                {{ $tipoDocumento->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Contenedor del switch "Es tienda" -->
                                <div id="esTiendaContainer" class="hidden mt-4">
                                    <label for="esTienda" class="block text-sm font-medium">¿Es tienda?</label>
                                    <div class="flex items-center">
                                        <!-- Campo hidden para enviar valor 0 si el switch no está activado -->
                                        <input type="hidden" name="esTienda" value="0">
                                        <div class="w-12 h-6 relative">
                                            <input type="checkbox" id="esTienda" name="esTienda"
                                                class="custom_switch absolute w-full h-full opacity-0 z-10 cursor-pointer peer"
                                                value="1" />
                                            <span for="esTienda"
                                                class="bg-[#ebedf2] dark:bg-dark block h-full rounded-full before:absolute before:left-1 before:bg-white dark:before:bg-white-dark dark:peer-checked:before:bg-white before:bottom-1 before:w-4 before:h-4 before:rounded-full peer-checked:before:left-7 peer-checked:bg-primary before:transition-all before:duration-300"></span>
                                        </div>
                                    </div>
                                </div>


                                <!-- Documento -->
                                <div>
                                    <label for="documento" class="block text-sm font-medium">Documento</label>
                                    <input id="documento" type="text" name="documento" class="form-input w-full"
                                        placeholder="Ingrese el documento">
                                </div>
                                <!-- Teléfono -->
                                <div>
                                    <label for="telefono" class="block text-sm font-medium">Teléfono</label>
                                    <input id="telefono" type="text" name="telefono" class="form-input w-full"
                                        placeholder="Ingrese el teléfono">
                                </div>
                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium">Email</label>
                                    <input id="email" type="email" class="form-input w-full" name="email"
                                        placeholder="Ingrese el email">
                                </div>
                                <!-- departamento -->
                                <div>
                                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                                    <select id="departamento" name="departamento" class="form-input w-full">
                                        <option value="" disabled selected>Seleccionar Departamento</option>
                                        @foreach ($departamentos as $departamento)
                                            <option value="{{ $departamento['id_ubigeo'] }}">
                                                {{ $departamento['nombre_ubigeo'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Provincia -->
                                <div>
                                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                                    <select id="provincia" name="provincia" class="form-input w-full" disabled>
                                        <option value="" disabled selected>Seleccionar Provincia</option>
                                    </select>
                                </div>

                                <!-- Distrito -->
                                <div>
                                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                                    <select id="distrito" name="distrito" class="form-input w-full" disabled>
                                        <option value="" disabled selected>Seleccionar Distrito</option>
                                    </select>
                                </div>
                                <!-- Dirección (Ocupa 2 columnas) -->
                                <div>
                                    <label for="direccion" class="block text-sm font-medium">Dirección</label>
                                    <input id="direccion" type="text" name="direccion" class="form-input w-full"
                                        placeholder="Ingrese el direccion">
                                </div>
                            </div>
                            <!-- Botones -->
                            <div class="flex justify-end items-center mt-4">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="open = false">Cancelar</button>
                                <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
    <script src="{{ asset('assets/js/tickets/smart/configuraciones.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>


    <script>
    // Función para mostrar un toastr de error
    function showToast(message) {
        toastr.error(message, "Error", {
            positionClass: "toast-top-right", // Posición en la pantalla
            timeOut: 3000, // Duración de la notificación
            closeButton: true
        });
    }

    // Validación en tiempo real para el número de ticket
    document.getElementById('nroTicket').addEventListener('input', function() {
        const inputTicket = document.getElementById('nroTicket');
        const errorTicket = document.getElementById('errorTicket');
        const nroTicketValue = inputTicket.value.trim();

        if (nroTicketValue === "") {
            inputTicket.classList.remove('border-red-500', 'border-green-500');
            errorTicket.textContent = "Campo vacío";  // Mostrar mensaje de campo vacío
            errorTicket.classList.remove('hidden');
        } else {
            fetch(`/validar-ticket/${nroTicketValue}`)
                .then(response => response.json())
                .then(data => {
                    if (data.existe) {
                        inputTicket.classList.add('border-red-500');
                        inputTicket.classList.remove('border-green-500');
                        errorTicket.textContent = 'El número de ticket ya está en uso. Por favor, ingrese otro número.';
                        errorTicket.classList.remove('hidden');
                        // Mostrar toastr
                        showToast('El número de ticket ya está en uso. Por favor, ingrese otro número.');
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
                    showToast('Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.');
                });
        }
    });

    // Validación en tiempo real para campos obligatorios
    const camposObligatorios = [
        'idCliente', 'idClienteGeneral', 'idTienda', 
        'direccion', 'fechaCompra', 'idMarca', 'idModelo', 'serie', 'fallaReportada'
    ];

    camposObligatorios.forEach(campo => {
        const input = document.getElementById(campo);

        // Agregar evento de "input" o "change" para validación en tiempo real
        input.addEventListener('input', function() {
            validateCampo(input);
        });

        // Para campos de tipo "select" o "date", se usa el evento "change"
        if (input.tagName === 'SELECT' || input.type === 'date') {
            input.addEventListener('change', function() {
                validateCampo(input);
            });
        }

        // También puedes agregar evento "focusout" para perder foco (cuando el usuario termina de escribir)
        input.addEventListener('focusout', function() {
            validateCampo(input);
        });
    });

    // Función para validar el campo y mostrar/ocultar el mensaje "Campo vacío"
    function validateCampo(input) {
        const errorId = `error-${input.id}`;
        let errorText = document.getElementById(errorId);
        
        if (!input.value.trim()) {
            // Si está vacío
            input.classList.add('border-red-500');
            if (!errorText) {
                errorText = document.createElement('p');
                errorText.id = errorId;
                errorText.classList.add('text-sm', 'text-red-500', 'mt-1');
                input.parentNode.appendChild(errorText);
            }
            errorText.textContent = "Campo vacío";
        } else {
            // Si tiene contenido
            input.classList.remove('border-red-500');
            if (errorText) {
                errorText.remove();
            }
        }
    }

    // Validación al enviar el formulario
    document.getElementById('ordenTrabajoForm').addEventListener('submit', function(event) {
        let errorFound = false;
        let errorMessages = []; // Para almacenar los mensajes de error

        // Validar todos los campos obligatorios antes de enviar
        camposObligatorios.forEach(campo => {
            const input = document.getElementById(campo);
            if (input.value.trim() === "") {
                errorFound = true;
                validateCampo(input); // Ejecuta la validación en caso de que falte contenido
                errorMessages.push(`El campo ${input.id} está vacío. Por favor, complete el campo.`);
            }
        });

        // Validación específica para nroTicket (si está vacío o con error)
        const nroTicketInput = document.getElementById('nroTicket');
        const errorTicket = document.getElementById('errorTicket');
        if (nroTicketInput.value.trim() === "" || nroTicketInput.classList.contains('border-red-500')) {
            errorFound = true;
            errorTicket.classList.remove('hidden');
            errorMessages.push('El número de ticket está vacío o inválido.');
        }

        // Si hay errores, mostrar solo el primer mensaje de error
        if (errorFound) {
            event.preventDefault();
            // Mostrar el primer mensaje de error en el toastr
            showToast(errorMessages[0]);
        }
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Evento para cuando se ingresa un número de serie
    document.getElementById('serie').addEventListener('input', function() {
        let serie = this.value.trim();  // Obtener el valor de la serie
        let select = document.getElementById('selectTickets'); // El select donde se mostrarán los tickets

        // Si el campo serie no está vacío
        if (serie.length > 0) { 
            fetch(`/tickets-por-serie/${serie}`)
                .then(response => response.json())
                .then(data => {
                    // Limpiar las opciones previas
                    select.innerHTML = '<option value="" selected>Seleccionar Ticket</option>';

                    // Si hay tickets asociados a la serie, los mostramos
                    if (data.length > 0) {
                        // Mostrar el select de tickets
                        select.style.display = 'block'; // Mostrar el select

                        data.forEach(ticket => {
                            let option = document.createElement('option');
                            option.value = ticket.idTickets; // El valor será el id del ticket
                            option.textContent = `Ticket N°: ${ticket.numero_ticket} - Fecha: ${ticket.fecha_creacion}`; // Mostrar detalles del ticket
                            select.appendChild(option);
                        });
                    } else {
                        // Si no hay tickets, mostrar un mensaje y ocultar el select
                        select.style.display = 'none'; // Ocultar el select

                        let option = document.createElement('option');
                        option.value = "";
                        option.textContent = "No hay tickets asociados a esta serie";
                        select.appendChild(option);
                    }
                })
                .catch(error => console.error('Error al cargar los tickets:', error));
        } else {
            // Limpiar el select y ocultarlo si no se ingresa una serie
            select.style.display = 'none'; // Ocultar el select
            select.innerHTML = '<option value="" selected>Seleccionar Ticket</option>'; // Limpiar las opciones previas
        }
    });

    // Evento para cuando se selecciona un ticket
    document.getElementById('selectTickets').addEventListener('change', function() {
        let ticketId = this.value; // Obtener el id del ticket seleccionado
        if (ticketId) {
            // Redirigir a la página de edición del ticket en una nueva pestaña
            window.open(`/ordenes/smart/${ticketId}/edit`, '_blank');  // Abre la URL en una nueva pestaña
        }
    });
});
</script>



<script>
    
</script>


    


</x-layout.default>
