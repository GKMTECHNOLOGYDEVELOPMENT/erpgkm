<x-layout.default>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nice-select2/dist/css/nice-select2.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        .panel {
            overflow: visible !important;
            /* Asegura que el modal no restrinja contenido */
        }

        #map {
            height: 400px;
            width: 100%;
        }
    </style>
    <div>
        <ul class="flex space-x-2 rtl:space-x-reverse mt-4">
            <li>
                <a href="{{ route('administracion.tienda') }}" class="text-primary hover:underline">Tienda</a>
            </li>
            <li class="before:content-['/'] ltr:before:mr-1 rtl:before:ml-1">
                <span>Agregar Tienda</span>
            </li>
        </ul>
    </div>
    <div class="panel mt-6 p-5 max-w-4x2 mx-auto">
        <h2 class="text-xl font-bold mb-5">Agregar Tienda</h2>

 
        <!-- Formulario -->
        <div class="p-5">
            <form id="tiendaForm" class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST"
                action="{{ route('tiendas.store') }}">
                @csrf
                <!-- RUC -->
                <div>
                    <label for="ruc" class="block text-sm font-medium">RUC</label>
                    <input id="ruc" name="ruc" type="text" class="form-input w-full"
                        placeholder="Ingrese el RUC" pattern="^\d{8,}$" required
                        title="Solo se permiten números y debe ser mayor a 7 digitos">
                    <div id="ruc-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>

                <!-- Nombre -->
                <div>
                    <label for="nombre" class="block text-sm font-medium">Nombre</label>
                    <input id="nombre" type="text" class="form-input w-full" placeholder="Ingrese el nombre"
                        name="nombre">
                    <div id="nombre-error" class="text-red-500 text-sm" style="display: none;"></div>

                </div>
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium">Email</label>
                    <input id="email" type="email" class="form-input w-full" placeholder="Ingrese el email"
                        name="email" 
                        title="Por favor, ingresa un correo electrónico válido. Ejemplo: usuario@dominio.com">
                    <div id="email-error" class="text-red-500 text-sm" style="display: none;"></div>

                </div>


                <div>
                    <label for="idCliente" class="block text-sm font-medium">Cliente</label>
                    <select id="idCliente" name="idCliente" class="select2 w-full" style="display:none">
                        <option value="" disabled selected>Seleccionar Cliente</option>
                        <!-- Llenar el select con clientes dinámicamente -->
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->idCliente }}"
                                {{ old('idCliente') == $cliente->idCliente ? 'selected' : '' }}>
                                {{ $cliente->nombre }} - {{ $cliente->documento }}
                            </option>
                        @endforeach
                    </select>
                    <div id="idCliente-error" class="text-red-500 text-sm" style="display: none;"></div>
        
                </div>

                <!-- Celular -->

                <div>
                    <label for="celular" class="block text-sm font-medium">Celular</label>
                    <input id="celular" type="text" class="form-input w-full" placeholder="Ingrese el celular"
                        name="celular"  pattern="^\d{8,}$"
                        title="El número de celular debe contener solo números y ser mayor a 7 dígitos">
                    <div id="celular-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>



                <!-- departamento -->
                <div>
                    <label for="departamento" class="block text-sm font-medium">Departamento</label>
                    <select id="departamento" name="departamento" class="form-input w-full">
                        <option value="" disabled selected>Seleccionar Departamento</option>
                        @foreach ($departamentos as $departamento)
                            <option value="{{ $departamento['id_ubigeo'] }}">{{ $departamento['nombre_ubigeo'] }}
                            </option>
                        @endforeach
                    </select>
                    <div id="departamento-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>

                <!-- Provincia -->
                <div>
                    <label for="provincia" class="block text-sm font-medium">Provincia</label>
                    <select id="provincia" name="provincia" class="form-input w-full" disabled>
                        <option value="" disabled selected>Seleccionar Provincia</option>
                    </select>
                    <div id="provincia-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>

                <!-- Distrito -->
                <div>
                    <label for="distrito" class="block text-sm font-medium">Distrito</label>
                    <select id="distrito" name="distrito" class="form-input w-full" disabled>
                        <option value="" disabled selected>Seleccionar Distrito</option>
                    </select>
                    <div id="distrito-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>

                <div>
                    <label for="dirrecion" class="block text-sm font-medium">Dirección</label>
                    <input id="dirrecion" type="text" class="form-input w-full"
                        placeholder="Ingrese el nombre del contacto" name="direccion">

                    <div id="dirrecion-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Referencia -->
                <div>
                    <label for="referencia" class="block text-sm font-medium">Link de Ubicación</label>
                    <input id="referencia" type="text" class="form-input w-full" placeholder="Ingrese el link de Google Maps"
                        name="referencia">

                        <div id="referencia-error" class="text-red-500 text-sm" style="display: none;"></div>
                </div>
                <!-- Latitud -->
                <div>
                    <label for="latitud" class="block text-sm font-medium">Latitud</label>
                    <input id="latitud" type="text" class="form-input w-full" placeholder="Latitud"
                        name="lat">
                    <!-- <div id="latitud-error" class="text-red-500 text-sm" style="display: none;"></div> -->
                </div>
                <!-- Longitud -->
                <div>
                    <label for="longitud" class="block text-sm font-medium">Longitud</label>
                    <input id="longitud" type="text" name="lng" class="form-input w-full"
                        placeholder="Longitud">
                    <!-- <div id="longitud-error" class="text-red-500 text-sm" style="display: none;"></div> -->
                </div>
                <input id="mapSearchBox" class="form-control" type="text" placeholder="Buscar lugar..." style="width: 100%; max-width: 400px; margin-bottom: 10px;">

                <!-- Mapa -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium">Mapa</label>
                    <div id="map" class="w-full h-64 rounded border"></div>
                </div>
                <!-- Botones -->
                <div class="md:col-span-2 flex justify-end mt-4">
                    <a href="{{ route('administracion.tienda') }}" class="btn btn-outline-danger">Cancelar</a>
                    <button type="submit" id="guardarBtn" class="btn btn-primary ltr:ml-4 rtl:mr-4">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/nice-select2/dist/js/nice-select2.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script async
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyD1XZ84dlEl7hAAsMR-myjaMpPURq5G3tE&libraries=places&callback=initMap">
</script>


<!-- SCRIPT FINAL -->
<script>
    let map, marker, geocoder, autocomplete;

    function initMap() {
        const latInput = document.getElementById("latitud");
        const lngInput = document.getElementById("longitud");
        const linkInput = document.getElementById("referencia");
        const direccionInput = document.getElementById("dirrecion");
        const mapContainer = document.getElementById("map");
        const input = document.getElementById("mapSearchBox");

        const initialLat = parseFloat(latInput.value) || -11.957242;
        const initialLng = parseFloat(lngInput.value) || -77.0731862;

        map = new google.maps.Map(mapContainer, {
            center: { lat: initialLat, lng: initialLng },
            zoom: 15,
        });

        marker = new google.maps.Marker({
            position: { lat: initialLat, lng: initialLng },
            map: map,
            draggable: true,
        });

        geocoder = new google.maps.Geocoder();

        // 🔄 Actualiza inputs + dirección
        function updateInputs(lat, lng, direccion = "") {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            linkInput.value = `https://www.google.com/maps?q=${lat},${lng}`;
            if (direccion) {
                direccionInput.value = direccion;
            } else {
                getAddressFromCoords(lat, lng);
            }
        }

        // 🔁 Geocodificación inversa
        function getAddressFromCoords(lat, lng) {
            geocoder.geocode({ location: { lat, lng } }, (results, status) => {
                if (status === "OK" && results[0]) {
                    const direccion = results[0].formatted_address;
                    direccionInput.value = direccion;
                    marker.setTitle(direccion);
                } else {
                    console.warn("⚠️ Dirección no encontrada:", status);
                }
            });
        }

        // 🖱 Clic en el mapa
        map.addListener("click", function (event) {
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();
            marker.setPosition({ lat, lng });
            updateInputs(lat, lng);
        });

        // 📍 Drag marker
        marker.addListener("dragend", () => {
            const pos = marker.getPosition();
            updateInputs(pos.lat(), pos.lng());
        });

        // 🔍 Autocompletado
        autocomplete = new google.maps.places.Autocomplete(input);
        autocomplete.bindTo("bounds", map);

        autocomplete.addListener("place_changed", () => {
            const place = autocomplete.getPlace();
            if (!place.geometry || !place.geometry.location) return;

            const loc = place.geometry.location;
            map.panTo(loc);
            map.setZoom(17);
            marker.setPosition(loc);
            updateInputs(loc.lat(), loc.lng(), place.formatted_address || "");
        });

        // ✏️ Inputs de coordenadas
        function updateMarker() {
            const lat = parseFloat(latInput.value);
            const lng = parseFloat(lngInput.value);
            if (!isNaN(lat) && !isNaN(lng)) {
                const newPos = { lat, lng };
                marker.setPosition(newPos);
                map.setCenter(newPos);
                getAddressFromCoords(lat, lng);
            }
        }

        latInput.addEventListener("change", updateMarker);
        lngInput.addEventListener("change", updateMarker);

        // URL Maps -> Coordenadas
        function extractCoordinates(url) {
            let lat, lng;
            if (url.includes("/search/")) {
                let clean = url.split("/search/")[1].split("?")[0].replace(/\+/g, " ");
                let coords = clean.split(",").map(c => c.trim());
                if (coords.length === 2) {
                    lat = parseFloat(coords[0]);
                    lng = parseFloat(coords[1]);
                }
            }
            if (!lat || !lng) {
                let regex = /!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/g;
                let matches = [...url.matchAll(regex)];
                if (matches.length > 0) {
                    let last = matches[matches.length - 1];
                    lat = parseFloat(last[1]);
                    lng = parseFloat(last[2]);
                }
            }

            if (!isNaN(lat) && !isNaN(lng)) {
                marker.setPosition({ lat, lng });
                map.setCenter({ lat, lng });
                updateInputs(lat, lng);
            } else {
                console.warn("⚠️ Coordenadas inválidas en el link");
            }
        }

        async function expandShortURL(shortURL) {
            try {
                const response = await fetch(`http://127.0.0.1:8000/ubicacion/direccion.php?url=${encodeURIComponent(shortURL)}`);
                const data = await response.json();
                if (data.expanded_url) {
                    extractCoordinates(data.expanded_url);
                }
            } catch (err) {
                console.error("❌ Error al expandir URL:", err);
            }
        }

        linkInput.addEventListener("change", () => {
            const link = linkInput.value.trim();
            if (link.includes("maps.app.goo.gl")) {
                expandShortURL(link);
            } else {
                extractCoordinates(link);
            }
        });

        // Primera carga
        updateInputs(initialLat, initialLng);
    }

            // ✅ Select2 personalizado
            document.querySelectorAll('.select2').forEach(function (select) {
            NiceSelect.bind(select, { searchable: true });
        });
    
</script>


<script>
    $(document).ready(function() {
        let formValid = true; // Bandera que indica si el formulario es válido

        // Validar campos vacíos
        function checkEmptyFields() {
            formValid = true; // Asumimos que el formulario es válido inicialmente

            console.log("Verificando campos vacíos..."); // Log para ver si estamos entrando en la función

            // Definir los campos a validar (sin email ni celular)
            const camposRequeridos = [
                '#ruc', '#nombre', '#referencia', '#dirrecion',
                '#departamento', '#provincia', '#distrito', '#cliente', '#idCliente'
            ];

            // Comprobar si algún campo requerido está vacío
            camposRequeridos.forEach(function(campo) {
                console.log("Verificando campo: " + campo); // Ver qué campo estamos verificando

                if ($(campo).is('select')) {
                    // Validación para campos de tipo select (comprobar si no se seleccionó una opción válida)
                    if ($(campo).val() === "" || $(campo).val() === null) {
                        formValid = false; // Si algún campo está vacío, desactivar el envío
                        $(campo).addClass('border-red-500'); // Marcar el campo con borde rojo
                        $(campo).siblings('.text-red-500').text('Este campo es obligatorio')
                            .show(); // Mostrar el mensaje de error
                        console.log("Campo vacío: " + campo); // Log para mostrar el campo vacío
                    } else {
                        $(campo).removeClass('border-red-500'); // Quitar el borde rojo si no está vacío
                        $(campo).siblings('.text-red-500')
                            .hide(); // Ocultar el mensaje de error si no está vacío
                    }
                } else {
                    // Validación para otros tipos de campos (input)
                    if ($(campo).val() === '') {
                        formValid = false; // Si algún campo está vacío, desactivar el envío
                        $(campo).addClass('border-red-500'); // Marcar el campo con borde rojo
                        $(campo).siblings('.text-red-500').text('Este campo es obligatorio')
                            .show(); // Mostrar el mensaje de error
                        console.log("Campo vacío: " + campo); // Log para mostrar el campo vacío
                    } else {
                        $(campo).removeClass('border-red-500'); // Quitar el borde rojo si no está vacío
                        $(campo).siblings('.text-red-500')
                            .hide(); // Ocultar el mensaje de error si no está vacío
                    }
                }
            });
        }

        // Añadir evento para los select
        $('#departamento, #provincia, #distrito, #idCliente, #dirrecion, #referencia').on('change', function() {
            checkEmptyFields(); // Revalidar campos vacíos cada vez que cambie la selección
        });

        // Interceptar el envío del formulario
        $('#tiendaForm').submit(function(event) {
            console.log("Formulario a enviar..."); // Log para indicar que estamos interceptando el envío
            checkEmptyFields(); // Verificar si hay campos vacíos antes de enviar

            if (!formValid) {
                event.preventDefault(); // Evitar el envío del formulario
                console.log("Formulario no válido, se ha bloqueado el envío"); // Log para ver que el formulario no es válido

                // Crear el div de la alerta
            

                // Insertar el div de la alerta en el DOM (por ejemplo, al principio del formulario)
                // $('#tiendaForm').before(alertDiv);
            } else {
                console.log("Formulario válido, se enviará"); // Log para ver que el formulario es válido
            }
        });

        // Validar RUC en tiempo real
        $('#ruc').on('input', function() {
            let ruc = $(this).val();
            console.log("Verificando RUC: " + ruc); // Log para ver el valor del RUC

            // Verificar si el RUC contiene solo números
            if (/[^0-9]/.test(ruc)) {
                $('#ruc').addClass('border-red-500');
                $('#ruc-error').text('El RUC solo debe contener números').show();
                formValid = false;
                return;
            } else {
                $('#ruc').removeClass('border-red-500');
                $('#ruc-error').hide();
            }

            // Verificar que el RUC tenga más de 8 dígitos
            if (ruc.length < 8) {
                $('#ruc').addClass('border-red-500');
                $('#ruc-error').text('El RUC debe tener al menos 8 dígitos').show();
                formValid = false;
                return;
            } else {
                $('#ruc').removeClass('border-red-500');
                $('#ruc-error').hide();
            }

            // Si el RUC tiene más de 8 dígitos, proceder con la validación en el servidor
            $.post('{{ route('validar.ruc') }}', {
                ruc: ruc,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                console.log("Respuesta RUC: ", response); // Log para ver la respuesta del servidor
                if (response.exists) {
                    $('#ruc').addClass('border-red-500');
                    $('#ruc-error').text('El RUC ya está registrado').show();
                    formValid = false; // Desactivar el envío del formulario
                } else {
                    $('#ruc').removeClass('border-red-500');
                    $('#ruc-error').hide();
                    checkEmptyFields(); // Revalidar campos vacíos
                }
            });
        });

        // Validar Nombre en tiempo real
        $('#nombre').on('input', function() {
            let nombre = $(this).val();
            console.log("Verificando Nombre: " + nombre); // Log para ver el valor del nombre

            $.post('{{ route('validar.nombre') }}', {
                nombre: nombre,
                _token: '{{ csrf_token() }}'
            }, function(response) {
                console.log("Respuesta Nombre: ", response); // Log para ver la respuesta del servidor
                if (response.exists) {
                    $('#nombre').addClass('border-red-500');
                    $('#nombre-error').text('El nombre ya está registrado').show();
                    formValid = false; // Desactivar el envío del formulario
                } else {
                    $('#nombre').removeClass('border-red-500');
                    $('#nombre-error').hide();
                    checkEmptyFields(); // Revalidar campos vacíos
                }
            });
        });

    });
</script>



<script>
    // Al cargar el documento
document.addEventListener('DOMContentLoaded', function() {
    const clienteSelect = document.getElementById('idCliente');
    const rucInput = document.getElementById('ruc');

    // Esta función se ejecuta cuando el cliente es seleccionado
    clienteSelect.addEventListener('change', function() {
        // Obtén el id del cliente seleccionado
        const clienteId = clienteSelect.value;

        // Aquí mapeamos los clientes ya cargados en la página (por ejemplo, con un atributo data-)
        const clientes = @json($clientes); // Pasamos el array de clientes desde el backend a JavaScript

        // Buscar el cliente seleccionado por id
        const clienteSeleccionado = clientes.find(cliente => cliente.idCliente == clienteId);

        // Si encontramos al cliente, ponemos el RUC en el input
        if (clienteSeleccionado) {
            rucInput.value = clienteSeleccionado.documento; // Asignamos el RUC al campo
        }
    });
});

</script>
    <!-- <script src="{{ asset('assets/js/tienda/tiendavalidaciones.js') }}"></script> -->
    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
</x-layout.default>
