<x-layout.default>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />

    <style>
        #map {
            height: 300px;
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

    <div class="panel mt-6 p-5 max-w-4xl mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Orden de Trabajo</h2>

        @if (session('success'))
            <div class="alert alert-success mb-4">
                <strong>Éxito!</strong> {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger mb-4">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif

        <div class="p-5">
            <form id="ordenTrabajoForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('ordenes.store') }}">
                @csrf

                <!-- Número de Ticket -->
                <div>
                    <label for="nroTicket" class="block text-sm font-medium">N. Ticket</label>
                    <input id="nroTicket" name="nroTicket" type="text" class="form-input w-full"
                        placeholder="Ingrese el número de ticket">
                </div>

                <!-- Cliente General -->
                <div>
                    <label for="idClienteGeneral" class="block text-sm font-medium">Cliente General</label>
                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full" style="display: none">
                        <option value="" disabled selected>Seleccionar Cliente General</option>
                        @foreach ($clientesGenerales as $cliente)
                            <option value="{{ $cliente->idClienteGeneral }}">{{ $cliente->descripcion }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Cliente -->
                <div>
                    <label for="idCliente" class="block text-sm font-medium">Cliente</label>
                    <select id="idCliente" name="idCliente" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Cliente</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->idCliente }}" data-tienda="{{ $cliente->esTienda }}">
                                {{ $cliente->nombre }} - {{ $cliente->documento }}
                            </option>
                        @endforeach
                    </select>
                    
                </div>

                <!-- Tienda -->
                <div>
                    <label for="idTienda" class="block text-sm font-medium">Tienda</label>
                    <select id="idTienda" name="idTienda" class="select2 w-full" style="display: none">
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

                <!-- Marca -->
                <div>
                    <label for="idMarca" class="block text-sm font-medium">Marca</label>
                    <select id="idMarca" name="idMarca" class="select2 w-full" style="display: none">
                        <option value="" disabled selected>Seleccionar Marca</option>
                        @foreach ($marcas as $marca)
                            <option value="{{ $marca->idMarca }}">{{ $marca->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Modelo -->
                <div>
                    <label for="idModelo" class="block text-sm font-medium">Modelo</label>
                    <select id="idModelo" name="idModelo" class="select2 w-full" style="display: none">
                        <option value="" disabled selected>Seleccionar Modelo</option>
                        @foreach ($modelos as $modelo)
                            <option value="{{ $modelo->idModelo }}">{{ $modelo->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Serie -->
                <div>
                    <label for="serie" class="block text-sm font-medium">N. Serie</label>
                    <input id="serie" name="serie" type="text" class="form-input w-full"
                        placeholder="Ingrese la serie">
                </div>
                <!-- Técnico -->
                <div>
                    <label for="tecnico" class="block text-sm font-medium">Técnico</label>
                    <select id="tecnico" name="tecnico" class="select2 w-full" style="display: none">
                        <option value="" disabled selected>Seleccionar Técnico</option>
                        @foreach ($usuarios as $usuario)
                            <option value="{{ $usuario->idUsuario }}">{{ $usuario->nombre }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Fecha de Compra -->

                <div>
                    <label for="fechaCompra" class="block text-sm font-medium">Fecha de Compra</label>
                    <input id="fechaCompra" name="fechaCompra" type="text" class="form-input w-full"
                        placeholder="Seleccionar fecha">
                </div>

                <!-- Falla Reportada -->
                <div class="col-span-2">
                    <label for="fallaReportada" class="block text-sm font-medium">Falla Reportada</label>
                    <textarea id="fallaReportada" name="fallaReportada" rows="3" class="form-input w-full"
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
                <div class="md:col-span-2">
                    <div id="map" class="w-full h-64 rounded border"></div>
                </div>

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
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });

            const map = L.map('map').setView([-12.0464, -77.0428], 13);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            let marker;
            map.on('click', function(e) {
                document.getElementById('latitud').value = e.latlng.lat;
                document.getElementById('longitud').value = e.latlng.lng;
                if (marker) marker.setLatLng(e.latlng);
                else marker = L.marker(e.latlng).addTo(map);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#fechaCompra", {
                dateFormat: "d/m/Y", // Formato de fecha DD/MM/AAAA
                altInput: true, // Muestra un input alternativo con formato legible
                altFormat: "F j, Y", // Formato legible como "Enero 1, 2025"
                locale: "es", // Configura en español
                allowInput: true, // Permite escribir manualmente la fecha
                disableMobile: "true" // Evita que los dispositivos móviles usen su picker nativo
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const selectCliente = document.getElementById("idCliente");
            const tiendaField = document.getElementById("idTienda").closest("div");
            const latitudField = document.getElementById("latitud").closest("div");
            const longitudField = document.getElementById("longitud").closest("div");
            const mapaField = document.getElementById("map").closest("div");

            function verificarClienteEsTienda() {
                // Obtener el cliente seleccionado
                const clienteSeleccionado = selectCliente.options[selectCliente.selectedIndex];

                if (clienteSeleccionado) {
                    // Obtener el atributo data-tienda del option seleccionado
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

            // Ejecutar la verificación cuando cambie el select de cliente
            selectCliente.addEventListener("change", verificarClienteEsTienda);

            // Verificar al cargar la página en caso de edición
            verificarClienteEsTienda();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
</x-layout.default>
