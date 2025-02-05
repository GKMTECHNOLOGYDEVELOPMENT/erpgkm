<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

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

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Mostrar mensaje de éxito si hay una variable de sesión 'success' -->
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif


    <!-- Contenedor principal -->
    <div x-data="{ openClienteModal: false }" class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Orden de Trabajo</h2>

     

        <div class="p-5">
            <form id="ordenTrabajoForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('ordenes.storesmart') }}">
                @csrf

                <!-- Número de Ticket -->
                <div>
                    <label for="nroTicket" class="block text-sm font-medium">N. Ticket</label>
                    <input id="nroTicket" name="nroTicket" type="text" class="form-input w-full"
                        placeholder="Ingrese el número de ticket">
                </div>

                
                <!-- Cliente: Seleccionar o crear nuevo -->
                <div class="col-span-1">
                    <div class="flex items-center space-x-2">
                        <label for="idCliente" class="block text-sm font-medium">Cliente</label>
                        <button type="button" class="btn btn-primary p-1 mb-2" @click="openClienteModal = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-3 h-3" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <select id="idCliente" name="idCliente" class="select2 w-full">
                        <option value="" selected >Seleccionar Cliente </option>
                    </select>
                </div>

                <!-- Cliente General -->
                <div>
                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                    <select id="idClienteGeneral" name="idClienteGeneral" class="form-input w-full">
                        <option value="" selected>Seleccionar Cliente General</option>
                    </select>
                </div>

                <!-- Tienda -->
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
                    <button type="submit"  id="btnGuardar" class="btn btn-primary ml-4">Guardar</button>
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
                       <form class="p-5 space-y-4" id="clienteForm" method="POST" enctype="multipart/form-data" >
                            @csrf <!-- Asegúrate de incluir el token CSRF -->
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- ClienteGeneral -->
                                <div>
                                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                                    <select id="idClienteGeneraloption" name="idClienteGeneraloption[]"
                                        placeholder="Seleccionar Cliente General" multiple  class="select2 w-full">
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
                                    <label for="idTipoDocumento" class="block text-sm font-medium">Tipo Documento</label>
                                    <select id="idTipoDocumento" name="idTipoDocumento" class="select2 w-full" style="display:none">
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
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Nice Select en todos los selects con clase .select2
            document.querySelectorAll('.select2').forEach(function(select) {
                // console.log("Inicializando select:", select);
                NiceSelect.bind(select, {
                    searchable: true
                });
            });

            // Cambio de marca para cargar modelos vía AJAX
            $('#idMarca').change(function() {
                var idMarca = $(this).val();
                console.log("Marca seleccionada:", idMarca);
                if (idMarca) {
                    $.ajax({
                        url: '/modelos/' + idMarca,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            console.log("Respuesta AJAX:", data);
                            var $modeloSelect = $('#idModelo');
                            $modeloSelect.empty();
                            $modeloSelect.append(
                                '<option value="" disabled selected>Seleccionar Modelo</option>'
                            );
                            $.each(data, function(key, modelo) {
                                console.log("Agregando modelo:", modelo);
                                $modeloSelect.append('<option value="' + modelo
                                    .idModelo + '">' + modelo.nombre + '</option>');
                            });

                            // Si ya existe una instancia de NiceSelect, la destruimos (si el método destroy existe)
                            if ($modeloSelect.data('niceSelectInstance')) {
                                console.log("Destruyendo instancia previa de NiceSelect");
                                $modeloSelect.data('niceSelectInstance').destroy();
                            }
                            // Re-inicializamos el select con NiceSelect
                            var instance = new NiceSelect($modeloSelect[0], {
                                searchable: true
                            });
                            $modeloSelect.data('niceSelectInstance', instance);
                            console.log("Reinicializado el select de modelo con NiceSelect");
                        },
                        error: function(xhr, status, error) {
                            console.error("Error en AJAX:", error);
                        }
                    });
                } else {
                    console.log("No hay marca seleccionada, reiniciando select de modelo");
                    $('#idModelo').empty();
                    $('#idModelo').append('<option value="" disabled selected>Seleccionar Modelo</option>');
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar mapa con Leaflet
            const map = L.map('map').setView([-12.0464, -77.0428], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker;

            function buscarDireccion() {
                const direccion = document.getElementById("direccion").value.trim();
                if (direccion) {
                    const url =
                        `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(direccion)}`;
                    $.get(url, function(data) {
                        if (data && data.length > 0) {
                            const lat = data[0].lat;
                            const lon = data[0].lon;
                            map.setView([lat, lon], 13);
                            if (marker) {
                                marker.setLatLng([lat, lon]);
                            } else {
                                marker = L.marker([lat, lon]).addTo(map);
                            }
                            document.getElementById('latitud').value = lat;
                            document.getElementById('longitud').value = lon;
                        } else {
                            alert("No se encontraron resultados para esa dirección.");
                        }
                    });
                }
            }
            document.getElementById("direccion").addEventListener("input", function() {
                if (this.value.trim() !== "") {
                    buscarDireccion();
                }
            });
            map.on('click', function(e) {
                document.getElementById('latitud').value = e.latlng.lat;
                document.getElementById('longitud').value = e.latlng.lng;
                if (marker) {
                    marker.setLatLng(e.latlng);
                } else {
                    marker = L.marker(e.latlng).addTo(map);
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#fechaCompra", {
                dateFormat: "d/m/Y",
                altInput: true,
                altFormat: "F j, Y",
                locale: "es",
                allowInput: true,
                disableMobile: "true",
                onChange: function(selectedDates, dateStr, instance) {
                    document.getElementById("fechaCompra").value = instance.formatDate(selectedDates[0],
                        "Y-m-d");
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const selectCliente = document.getElementById("idCliente");
            const tiendaField = document.getElementById("idTienda").closest("div");
            const latitudField = document.getElementById("latitud").closest("div");
            const longitudField = document.getElementById("longitud").closest("div");
            const mapaField = document.getElementById("map").closest("div");

            function verificarClienteEsTienda() {
                const clienteSeleccionado = selectCliente.options[selectCliente.selectedIndex];
                if (clienteSeleccionado) {
                    const esTienda = clienteSeleccionado.dataset.tienda === "1";
                    if (esTienda) {
                        tiendaField.style.display = "none";
                        latitudField.style.display = "none";
                        longitudField.style.display = "none";
                        mapaField.style.display = "none";
                    } else {
                        tiendaField.style.display = "";
                        latitudField.style.display = "";
                        longitudField.style.display = "";
                        mapaField.style.display = "";
                    }
                }
            }
            selectCliente.addEventListener("change", verificarClienteEsTienda);
            verificarClienteEsTienda();
        });
        document.addEventListener("DOMContentLoaded", function() {
            const selectTipoDocumento = document.getElementById("idTipoDocumento");
            const esTiendaContainer = document.getElementById("esTiendaContainer");

            selectTipoDocumento.addEventListener("change", function() {
                const selectedText = selectTipoDocumento.options[selectTipoDocumento.selectedIndex].text
                    .trim();
                if (selectedText === "RUC") {
                    esTiendaContainer.classList.remove("hidden");
                } else {
                    esTiendaContainer.classList.add("hidden");
                }
            });
        });
    </script>

   


    
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        let clientesCargados = false; // Variable para verificar si los clientes ya fueron cargados

        // Función para cargar los clientes
        function cargarClientes() {
            console.log('Intentando cargar clientes...');
            fetch('/clientesdatoscliente')  // Llamada a la ruta que devuelve los clientes
                .then(response => response.json()) // Obtener los datos en formato JSON
                .then(data => {
                    console.log('Clientes recibidos:', data); // Ver los datos de los clientes
                    let select = document.getElementById('idCliente');
                    select.innerHTML = '<option value="" disabled selected>Seleccionar Cliente</option>'; // Limpiar las opciones anteriores

                    // Agregar las nuevas opciones
                    data.forEach(cliente => {
                        let option = document.createElement('option');
                        option.value = cliente.idCliente;
                        option.textContent = `${cliente.nombre} - ${cliente.documento}`;
                        option.setAttribute('data-tienda', cliente.esTienda);  // Agregar atributo de tienda si es necesario
                        select.appendChild(option);
                    });

                    // Mostrar el select después de cargar
                    select.style.display = 'block'; // Asegurarse de que el select se vea
                    select.style.visibility = 'visible'; // Hacerlo visible

                    // Inicializar NiceSelect en el select de clientes
                    NiceSelect.bind(select, {
                        searchable: true
                    });

                })
                .catch(error => {
                    console.error('Error al cargar clientes:', error);
                });
        }

        // Ocultar el select de clientes inicialmente
        let selectCliente = document.getElementById('idCliente');
        selectCliente.style.display = 'none'; // Esto oculta el primer select de "Cliente" al principio

        // Cargar los clientes solo si no se han cargado previamente
        if (!clientesCargados) {
            cargarClientes();
            clientesCargados = true;
        }

        // Evento para cuando se selecciona un cliente
        document.getElementById('idCliente').addEventListener('change', function () {
            let clienteId = this.value;
            if (clienteId) {
                console.log('Cliente seleccionado:', clienteId); // Verificar si el cliente es seleccionado
                fetch(`/clientes-generales/${clienteId}`)
                    .then(response => response.json())
                    .then(data => {
                        let select = document.getElementById('idClienteGeneral');
                        select.innerHTML = '<option value="" selected>Seleccionar Cliente General</option>'; // Limpiar

                        // Verificar si se recibió algún dato
                        console.log('Clientes generales:', data); // Verifica que se reciban los clientes generales

                        data.forEach(clienteGeneral => {
                            let option = document.createElement('option');
                            option.value = clienteGeneral.idClienteGeneral;
                            option.textContent = clienteGeneral.descripcion;
                            select.appendChild(option);
                        });

                        // No inicializamos NiceSelect en el select de Cliente General
                        // Simplemente utilizamos el select estándar
                    })
                    .catch(error => console.error('Error al cargar clientes generales:', error));
            } else {
                // Limpiar el select si no hay cliente seleccionado
                document.getElementById('idClienteGeneral').innerHTML = '<option value="" selected>Seleccionar Cliente General</option>';
            }
        });

        // Evento de envío del formulario de cliente
        document.getElementById('clienteForm').addEventListener('submit', function (event) {
            event.preventDefault();  // Evitar el envío normal del formulario

            let formData = new FormData(this); // Obtener los datos del formulario
            console.log('Datos del formulario:', Object.fromEntries(formData.entries()));  // Ver los datos del formulario

            fetch('/guardar-cliente', {
                method: 'POST',
                body: formData,  // Enviar los datos del formulario
            })
                .then(response => response.json())  // Parsear la respuesta como JSON
                .then(data => {
                    console.log('Respuesta del servidor (JSON):', data);  // Verificar la respuesta
                    if (data.errors) {
                        // Mostrar errores si los hay
                        mostrarErrores(data.errors);
                    } else {
                        // Mostrar mensaje de éxito
                        alert(data.message);

                        // Recargar los clientes después de guardar el cliente
                        cargarClientes();

                        // Limpiar el formulario y cerrar el modal si es necesario
                        document.getElementById('clienteForm').reset();
                        openClienteModal = false;  // Cerrar el modal si lo tienes
                    }
                })
                .catch(error => {
                    console.error('Error al guardar el cliente:', error);
                });
        });

    });
    </script>


<script>
        
        document.addEventListener("DOMContentLoaded", function() {
            // Inicializar nice-select2
            NiceSelect.bind(document.getElementById("idClienteGeneraloption"));

            const select = document.getElementById('idClienteGeneraloption');
            const selectedItemsContainer = document.getElementById('selected-items-list');

            // Función para actualizar los seleccionados
            function updateSelectedItems() {
                selectedItemsContainer.innerHTML = ''; // Limpiar el contenedor

                const selectedOptions = Array.from(select.selectedOptions); // Obtener las opciones seleccionadas

                selectedOptions.forEach(option => {
                    const badge = document.createElement('span');
                    badge.textContent = option.textContent;
                    badge.className = 'badge bg-primary'; // Aplicar el estilo del badge
                    selectedItemsContainer.appendChild(badge); // Agregar el badge al contenedor
                });
            }

            // Escuchar cambios en el select
            select.addEventListener('change', updateSelectedItems);

            // Actualizar los seleccionados al cargar la página
            updateSelectedItems();
        });
        document.addEventListener("DOMContentLoaded", function() {
            const tipoDocumento = document.getElementById("idTipoDocumento");
            const esTiendaContainer = document.getElementById("esTiendaContainer");

            tipoDocumento.addEventListener("change", function() {
                // Verificar si el texto del option seleccionado es "RUC"
                const selectedOptionText = tipoDocumento.options[tipoDocumento.selectedIndex].text;

                if (selectedOptionText === "RUC") {
                    esTiendaContainer.classList.remove("hidden"); // Muestra el switch
                } else {
                    esTiendaContainer.classList.add("hidden"); // Oculta el switch
                }
            });
        });
    </script>

<script>
    document.getElementById('btnGuardar').addEventListener('click', function (e) {
        e.preventDefault();

        const nroTicket = document.getElementById('nroTicket').value;

        fetch(`/validar-ticket/${nroTicket}`)
            .then(response => response.json())
            .then(data => {
                if (data.existe) {
                    // Usando showMessage para mostrar la alerta personalizada en rojo
                    showMessage(
                        'El número de ticket ya está en uso. Por favor, ingrese otro número.',
                        'top-end',
                        true, // Mostrar el botón de cierre
                        '',
                        5000, // Duración de la alerta
                        'error' // Tipo de alerta (error)
                    );
                } else {
                    document.getElementById('ordenTrabajoForm').submit();
                }
            })
            .catch(error => {
                console.error('Error al verificar el ticket:', error);
                showMessage(
                    'Ocurrió un error al verificar el ticket. Inténtelo de nuevo más tarde.',
                    'top-end',
                    true, // Mostrar el botón de cierre
                    '',
                    5000, // Duración de la alerta
                    'error' // Tipo de alerta (error)
                );
            });
    });

    // Función para mostrar la alerta con SweetAlert
    function showMessage(
        msg = 'Example notification text.',
        position = 'top-end',
        showCloseButton = true,
        closeButtonHtml = '',
        duration = 3000,
        type = 'success',
    ) {
        const toast = window.Swal.mixin({
            toast: true,
            position: position || 'top-end',
            showConfirmButton: false,
            timer: duration,
            showCloseButton: showCloseButton,
            icon: type === 'success' ? 'success' : 'error', // Cambia el icono según el tipo
            background: type === 'success' ? '#28a745' : '#dc3545', // Rojo para error, verde para éxito
            iconColor: 'white', // Color del icono
            customClass: {
                title: 'text-white', // Asegura que el texto sea blanco
            },
        });

        toast.fire({
            title: msg,
        });
    }
</script>



</x-layout.default>
