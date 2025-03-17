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

        <!-- Mostrar alertas de éxito o error -->
        @if (session('success'))
            <div class="alert alert-success mb-4">
                <strong>Éxito!</strong> {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="alert alert-danger mb-4">
                <strong>Error!</strong> {{ session('error') }}
            </div>
        @endif
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
                        name="email" required
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
                    <!-- @error('idCliente')
    <div class="text-red-500 text-sm">{{ $message }}</div>
@enderror -->
                </div>

                <!-- Celular -->

                <div>
                    <label for="celular" class="block text-sm font-medium">Celular</label>
                    <input id="celular" type="text" class="form-input w-full" placeholder="Ingrese el celular"
                        name="celular" required pattern="^\d{8,}$"
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

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            console.log("🔹 DOM completamente cargado");

            // Obtener referencias a los elementos
            const latInput = document.getElementById("latitud");
            const lngInput = document.getElementById("longitud");
            const linkInput = document.getElementById("referencia");
            const mapContainer = document.getElementById("map");

            if (!latInput || !lngInput || !linkInput || !mapContainer) {
                console.error("❌ No se encontraron los campos requeridos.");
                return;
            }

            console.log("✅ Campos de latitud, longitud y link de ubicación encontrados");

            // Coordenadas iniciales
            const initialLat = -11.957242;
            const initialLng = -77.0731862;

            console.log(`📍 Coordenadas iniciales: Lat: ${initialLat}, Lng: ${initialLng}`);

            // **Inicializar el mapa si aún no existe**
            if (!window.map || !document.getElementById("map")._leaflet_id) {
                console.log("🔄 Inicializando el mapa...");
                window.map = L.map("map").setView([initialLat, initialLng], 15);
                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: "&copy; OpenStreetMap contributors"
                }).addTo(window.map);
            } else {
                console.log("✅ Mapa ya estaba inicializado.");
            }

            // **Crear el marcador inicial y hacerlo arrastrable**
            let marker = L.marker([initialLat, initialLng], {
                draggable: true
            }).addTo(window.map);

            console.log("✅ Mapa y marcador inicializados correctamente");

            // **Actualizar el marcador y centrar el mapa**
            function updateMarker() {
                const lat = parseFloat(latInput.value);
                const lng = parseFloat(lngInput.value);

                console.log(`🔄 Intentando actualizar marcador a: Lat: ${lat}, Lng: ${lng}`);

                // Validar coordenadas
                if (!isNaN(lat) && !isNaN(lng) && lat >= -90 && lat <= 90 && lng >= -180 && lng <=
                    180) {
                    marker.setLatLng([lat, lng]); // Mover marcador
                    window.map.setView([lat, lng], 15); // Centrar el mapa
                    console.log("✅ Marcador y mapa actualizados correctamente");
                } else {
                    console.warn("⚠️ Coordenadas inválidas, el marcador no se actualizará");
                }
            }

            // **Extraer coordenadas correctas del link largo de Google Maps**
            function extractCoordinates(url) {
                console.log(`🔗 Analizando URL: ${url}`);

                let lat, lng;

                // **1️⃣ Si la URL tiene '/search/', limpiar y extraer coordenadas**
                if (url.includes("/search/")) {
                    console.log("🛠 Se detectó una URL con 'search/', limpiando...");

                    // Tomar solo lo que sigue después de "/search/"
                    let cleanURL = url.split("/search/")[1];

                    // Eliminar parámetros adicionales (como ?entry=tts)
                    cleanURL = cleanURL.split("?")[0];

                    // Reemplazar `+` por espacio en caso de que haya codificaciones extrañas
                    cleanURL = cleanURL.replace(/\+/g, " ");

                    // Separar la latitud y longitud (evitando espacios extra)
                    let coords = cleanURL.split(",").map(c => c.trim());

                    if (coords.length === 2) {
                        lat = parseFloat(coords[0]);
                        lng = parseFloat(coords[1]);

                        if (!isNaN(lat) && !isNaN(lng)) {
                            console.log(
                                `✅ Coordenadas extraídas después de limpiar: Lat: ${lat}, Lng: ${lng}`
                            );
                        } else {
                            console.warn("⚠️ No se pudieron convertir correctamente las coordenadas.");
                            return;
                        }
                    }
                }

                // **2️⃣ Intentar extraer coordenadas con la regex clásica (!3d...!4d...)**
                if (!lat || !lng) {
                    let regexClassic = /!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/g;
                    let matchesClassic = [...url.matchAll(regexClassic)];

                    if (matchesClassic.length > 0) {
                        let lastMatch = matchesClassic[matchesClassic.length -
                            1]; // Tomar la última coincidencia
                        lat = parseFloat(lastMatch[1]);
                        lng = parseFloat(lastMatch[2]);
                        console.log(
                            `✅ Coordenadas extraídas (formato clásico): Lat: ${lat}, Lng: ${lng}`);
                    }
                }

                // **3️⃣ Si no encontró coordenadas, mostrar advertencia**
                if (isNaN(lat) || isNaN(lng)) {
                    console.warn("⚠️ No se encontraron coordenadas válidas en la URL proporcionada.");
                    return;
                }

                // **Asignar valores a los inputs**
                latInput.value = lat;
                lngInput.value = lng;

                // **Mover el marcador en el mapa**
                updateMarker();
            }


            // **Llamar al backend para expandir el link corto**
            async function expandShortURL(shortURL) {
                console.log(`🔄 Intentando expandir el link corto: ${shortURL}`);

                try {
                    let response = await fetch(
                        `http://127.0.0.1:8000/ubicacion/direccion.php?url=${encodeURIComponent(shortURL)}`
                    );
                    let data = await response.json();

                    if (data.expanded_url) {
                        console.log(`✅ Link expandido: ${data.expanded_url}`);
                        extractCoordinates(data.expanded_url);
                    } else {
                        console.warn("⚠️ No se pudo expandir el link corto.");
                    }
                } catch (error) {
                    console.error("❌ Error al expandir el link corto:", error);
                }
            }

            // **Escuchar cambios en el campo de link**
            linkInput.addEventListener("change", function() {
                let link = linkInput.value.trim();

                if (link !== "") {
                    // **Si el link es corto, lo expandimos primero**
                    if (link.includes("maps.app.goo.gl")) {
                        expandShortURL(link);
                    } else {
                        extractCoordinates(link);
                    }
                }
            });

            // **Escuchar cambios en los inputs de latitud y longitud**
            latInput.addEventListener("change", updateMarker);
            lngInput.addEventListener("change", updateMarker);

            console.log("👂 Listos para escuchar cambios en los inputs");

            // **Cuando el usuario mueve el marcador, actualizar los inputs con la nueva posición**
            marker.on("dragend", function() {
                const position = marker.getLatLng();
                latInput.value = position.lat.toFixed(6);
                lngInput.value = position.lng.toFixed(6);
                console.log(
                    `🎯 Marcador movido manualmente: Lat: ${position.lat}, Lng: ${position.lng}`
                );
            });
        });


        // Inicializar Select2
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll('.select2').forEach(function(select) {
                NiceSelect.bind(select, {
                    searchable: true
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            let formValid = true; // Bandera que indica si el formulario es válido

            // Validar campos vacíos
            function checkEmptyFields() {
                formValid = true; // Asumimos que el formulario es válido inicialmente

                console.log("Verificando campos vacíos..."); // Log para ver si estamos entrando en la función

                // Definir los campos a validar
                const camposRequeridos = [
                    '#ruc', '#nombre', '#email', '#celular', '#referencia', '#dirrecion',
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
                console.log(
                    "Formulario a enviar..."); // Log para indicar que estamos interceptando el envío
                checkEmptyFields(); // Verificar si hay campos vacíos antes de enviar

                if (!formValid) {
                    event.preventDefault(); // Evitar el envío del formulario
                    console.log(
                        "Formulario no válido, se ha bloqueado el envío"
                    ); // Log para ver que el formulario no es válido

                    // Crear el div de la alerta
                    var alertDiv = $('<div>', {
                        class: 'flex items-center p-3.5 rounded text-warning bg-warning-light dark:bg-warning-dark-light'
                    }).html(`
            <span class="ltr:pr-2 rtl:pl-2">
                <strong class="ltr:mr-1 rtl:ml-1">Warning!</strong>
                Hay campos vacíos o repetidos. Por favor, corrija los errores y vuelva a intentarlo.
            </span>
            <button type="button" class="ltr:ml-auto rtl:mr-auto hover:opacity-80" onclick="this.parentElement.remove();">
                <svg> ... </svg>
            </button>
        `);

                    // Insertar el div de la alerta en el DOM (por ejemplo, al principio del formulario)
                    $('#tiendaForm').before(alertDiv);

                } else {
                    console.log(
                        "Formulario válido, se enviará"); // Log para ver que el formulario es válido
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
                    console.log("Respuesta RUC: ",
                        response); // Log para ver la respuesta del servidor
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
                    console.log("Respuesta Nombre: ",
                        response); // Log para ver la respuesta del servidor
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
            // Validar Email en tiempo real
            $('#email').on('input', function() {
                let email = $(this).val();
                console.log("Verificando Email: " + email); // Log para ver el valor del email

                // Verificar si el correo tiene un formato válido
                let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                if (!emailPattern.test(email)) {
                    $('#email').addClass('border-red-500');
                    $('#email-error').text('Por favor ingrese un correo válido').show();
                    formValid = false;
                    return;
                } else {
                    $('#email').removeClass('border-red-500');
                    $('#email-error').hide();
                }

                $.post('{{ route('validar.email') }}', {
                    email: email,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    console.log("Respuesta Email: ",
                        response); // Log para ver la respuesta del servidor
                    if (response.exists) {
                        $('#email').addClass('border-red-500');
                        $('#email-error').text('El correo electrónico ya está registrado').show();
                        formValid = false; // Desactivar el envío del formulario
                    } else {
                        $('#email').removeClass('border-red-500');
                        $('#email-error').hide();
                        checkEmptyFields(); // Revalidar campos vacíos
                    }
                });
            });
            // Validar Celular en tiempo real
            $('#celular').on('input', function() {
                let celular = $(this).val();
                console.log("Verificando Celular: " + celular); // Log para ver el valor del celular

                // Verificar si el celular contiene solo números
                if (/[^0-9]/.test(celular)) {
                    $('#celular').addClass('border-red-500');
                    $('#celular-error').text('El celular solo debe contener números').show();
                    formValid = false;
                    return;
                } else {
                    $('#celular').removeClass('border-red-500');
                    $('#celular-error').hide();
                }

                // Verificar que el celular tenga más de 8 dígitos
                if (celular.length < 9) { // Esto asegura que el celular tenga al menos 9 dígitos
                    $('#celular').addClass('border-red-500');
                    $('#celular-error').text('El celular debe tener al menos 9 dígitos').show();
                    formValid = false;
                    return;
                } else {
                    $('#celular').removeClass('border-red-500');
                    $('#celular-error').hide();
                }


                $.post('{{ route('validar.celular') }}', {
                    celular: celular,
                    _token: '{{ csrf_token() }}'
                }, function(response) {
                    console.log("Respuesta Celular: ",
                        response); // Log para ver la respuesta del servidor
                    if (response.exists) {
                        $('#celular').addClass('border-red-500');
                        $('#celular-error').text('El celular ya está registrado').show();
                        formValid = false; // Desactivar el envío del formulario
                    } else {
                        $('#celular').removeClass('border-red-500');
                        $('#celular-error').hide();
                        checkEmptyFields(); // Revalidar campos vacíos
                    }
                });
            });
        });
    </script>
    <!-- <script src="{{ asset('assets/js/tienda/tiendavalidaciones.js') }}"></script> -->
    <script src="{{ asset('assets/js/ubigeo.js') }}"></script>
</x-layout.default>
