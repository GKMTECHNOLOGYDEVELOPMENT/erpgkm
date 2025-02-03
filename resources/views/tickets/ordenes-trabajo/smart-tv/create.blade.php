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

    <!-- Contenedor principal -->
    <div x-data="{ openClienteModal: false }" class="panel mt-6 p-5 max-w-2xl mx-auto">
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
                action="{{ route('ordenes.storesmart') }}">
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
                    <select id="idClienteGeneral" name="idClienteGeneral" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Cliente General</option>
                        @foreach ($clientesGenerales as $cliente)
                            <option value="{{ $cliente->idClienteGeneral }}">{{ $cliente->descripcion }}</option>
                        @endforeach
                    </select>
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
                    <button type="submit" class="btn btn-primary ml-4">Guardar</button>
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
                        <form id="clienteForm" class="p-5 space-y-4" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                                                {{ $tipoDocumento->nombre }}</option>
                                        @endforeach
                                    </select>
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
                            </div>
                            <div class="flex justify-end items-center mt-4 gap-2">
                                <button type="button" class="btn btn-outline-danger"
                                    @click="openClienteModal = false">
                                    Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary ml-4">Guardar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts de inicialización -->
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <script>
        $(document).ready(function() {
            // Inicializar Nice Select en todos los selects con clase .select2
            document.querySelectorAll('.select2').forEach(function(select) {
                console.log("Inicializando select:", select);
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
</x-layout.default>
